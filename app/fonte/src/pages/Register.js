import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import TextField from '@material-ui/core/TextField';
import { FormControlLabel, Checkbox, Dialog, DialogTitle, DialogContent, DialogActions, Snackbar, Box } from '@material-ui/core';
import { MuiPickersUtilsProvider, KeyboardDatePicker } from '@material-ui/pickers';
import DateFnsUtils from "@date-io/date-fns";
import { ptBR } from "date-fns/locale";
import Grid from '@material-ui/core/Grid';
import { withStyles } from '@material-ui/core/styles';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import Typography from '@material-ui/core/Typography';
import Container from '@material-ui/core/Container';
import AppMenu from '../components/Menu';
import Services from "../Services";
import { cpfMask } from '../components/mask'
import { useStyles } from '../assets/estilos/pages/login';
import MuiAlert from '@material-ui/lab/Alert';
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import {
    BrowserView,
} from "react-device-detect";
import InputMask from 'react-input-mask';
import { logoBranca, logoLaranja } from '../assets/images/logo';

function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}


const theme = createTheme({
    palette: {
        primary: {
            light: '#FF8A00',
            main: '#FF8A00',
            dark: '#FF8A00',
            contrastText: '#444444',
        },
        secondary: {
            light: '#444444',
            main: '#444444',
            dark: '#444444',
            contrastText: '#fff',
        },
    },
});

class Register extends React.Component {

    state = {
        nome: '',
        email: '',
        error: '',
        open: false,
        openmodal: false,
        regulamento: '',
        buttonStyle: 'outlined',
        checkButton: false,
        data_nascimento: new Date(),
        cpf: "",
        telefone: "",
        senha: "",
        confirmaSenha: "",
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        loadingForm: false,
        redirect: '',
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0)
    }

    onChangeNome = e => {
        console.log(e.target.value);
        this.setState({ nome: e.target.value })
    }

    onChangeEmail = e => {
        console.log(e.target.value);
        this.setState({ email: e.target.value })
    }

    regulamento = () => {
        Services.regulamento(this);
    }

    responseRegulamento(response) {
        this.setState({ regulamento: response })
        this.setState({ open: true })
        this.setState({ buttonStyle: 'contained' })
    }

    handleClose = () => {
        this.setState({ open: false })
        this.setState({ checkButton: true })
    }

    handleCloseAlert = () => {
        if (this.state.alertStatus === 'success') {
            this.setState({ redirect: 'login' })
            return
        }
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
    }

    handleDateChange = (date) => {
        this.setState({ data_nascimento: date })
    };

    handleSubmit = e => {
        this.setState({ loadingForm: true })
        e.preventDefault();

        let cpf = this.state.cpf;

        if (this.state.checkButton === false) {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Tchê, confirma que tu leu e aceita os nossos termos de utilização." })
            this.setState({ alertStatus: 'error' })
            this.setState({ loadingForm: false })
            return;
        }

        this.setState({
            values: [{
                nome: this.state.nome,
                cpf: this.state.cpf,
                email: this.state.email,
                telefone: this.state.telefone,
                senha: this.state.senha,
                confirmasenha: this.state.confirmasenha,
                nascimento: this.state.data_nascimento.toLocaleDateString(),
                regulamento: 1,
            }]
        });

        Services.validaCpf(this, cpf);
    }

    handleInputChange = (event) => {
        if (event.target.name === 'nome') {
            this.setState({ nome: event.target.value })
            console.log(this.state.nome);
        }
        else if (event.target.name === 'email') {
            this.setState({ email: event.target.value })
        }
        else if (event.target.name === 'cpf') {
            this.setState({ cpf: cpfMask(event.target.value) })
        }
        else if (event.target.name === 'telefone') {
            this.setState({ telefone: event.target.value })
        }
        else if (event.target.name === 'senha') {
            this.setState({ senha: event.target.value })
        }
        else if (event.target.name === 'confirmasenha') {
            this.setState({ confirmasenha: event.target.value })
        }
        else if (event.target.name === 'termos') {
            this.setState({ checkButton: true })
        }
    }

    responseValidaCpf(response) {
        if (response.error === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            this.setState({ loadingForm: false })
            return
        }

        else {
            let values = this.state.values[0]
            Services.cadastro(this, values);
        }
    }

    responseCadastro(response) {
        this.setState({ loadingForm: false })
        if (response.error === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
        else {
            const values = [{
                auten: this.state.email,
                senha: this.state.senha,
            }];
            Services.login(this, values[0]);
        }
    }

    responseLogin(response) {
        if (response.error === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            this.setState({ loadingForm: false })
            return
        }
        else {
            window.localStorage.removeItem('token');
            localStorage.setItem('token', JSON.stringify(response.token));
            Services.usuarios(this, response.user.id, response.token);
        }
    }

    responseUsuarios(response) {
        this.setState({ loadingForm: false })
        let carrinho = localStorage.getItem('carrinhoList') || '';

        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }

        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }

        if (response.success === 'true' && carrinho === '') {
            window.localStorage.removeItem('user');
            localStorage.setItem('user', JSON.stringify(response));
            this.setState({ redirect: 'home' });
            this.oneSignalPush();
        }

        if (response.success === 'true' && carrinho !== '') {
            window.localStorage.removeItem('user');
            localStorage.setItem('user', JSON.stringify(response));
            this.setState({ redirect: 'sacola' });
            this.oneSignalPush();
        }

    }

    oneSignalPush = () => {
        if (window.cordova) {
          window.plugins.OneSignal
            .startInit("3b08a3d6-6ece-4c9f-9510-9c4c07551ab8")
            .handleNotificationOpened(this.notificationOpenedCallback)
            .endInit();
    
          window.plugins.OneSignal.setSubscription(true);
    
          window.plugins.OneSignal.getIds(function (ids) {
            let usuario = JSON.parse(localStorage.getItem('user')) || '';
            let token = JSON.parse(localStorage.getItem('token')) || '';
            let onesignal = ids.userId;
            Services.oneSignal(this, usuario.id, token, onesignal);
          });
    
        }
      }
    
      notificationOpenedCallback = (jsonData) => {
        console.log('notificationOpenedCallback: ' + JSON.stringify(jsonData));
      }

    render() {
        const { classes } = this.props;
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return (<div>
            <ThemeProvider theme={theme}>
                <div className={classes.root} >
                    <AppMenu />
                    <div className={classes.topSpace} />

                    <Container maxWidth="lg" className={classes.container}>
                        <Link to='/home'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
              </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="sm" className={classes.comingSoon}>
                        <CssBaseline />
                        <div className={classes.paper}>

                            <Box className="logoAvatar" alt="logo">
                                <img src={logoLaranja} alt="logo" />
                            </Box>

                            <Typography component="h1" variant="h6" align="center" className={classes.title} paragraph>
                                Cadastro
        </Typography>

                            <Typography component="h2" variant="subtitle1" align="center" className={classes.titleSmall} paragraph>
                                Registre-se e faça parte da Llevo:
        </Typography>
                            <form className={classes.form} onSubmit={this.handleSubmit} noValidate>
                                <Grid container spacing={2}>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="nome"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            id="nome"
                                            label="Nome"
                                            onChange={this.handleInputChange}
                                            value={this.state.nome}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            helperText={this.state.helperCPF}
                                            variant="outlined"
                                            required
                                            maxLenght="14"
                                            fullWidth
                                            id="cpf"
                                            label="CPF"
                                            name="cpf"
                                            value={this.state.cpf}
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <TextField
                                            variant="outlined"
                                            required
                                            fullWidth
                                            id="email"
                                            label="E-mail"
                                            name="email"
                                            value={this.state.email}
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <InputMask
                                            mask="(99) 99999-9999"
                                            maskChar={null}
                                            onChange={this.handleInputChange}
                                            value={this.state.telefone}
                                        >
                                            {() => <TextField
                                                variant="outlined"
                                                required
                                                fullWidth
                                                id="telefone"
                                                label="Telefone"
                                                name="telefone"
                                            />}
                                        </InputMask>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <MuiPickersUtilsProvider utils={DateFnsUtils} locale={ptBR}>
                                            <KeyboardDatePicker
                                                fullWidth
                                                inputVariant="outlined"
                                                id="date-picker-dialog"
                                                label="Data de nascimento"
                                                format="dd/MM/yyyy"
                                                value={this.state.data_nascimento}
                                                onChange={this.handleDateChange}
                                                KeyboardButtonProps={{
                                                    'aria-label': 'Data de nascimento',
                                                }}
                                            />
                                        </MuiPickersUtilsProvider>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <TextField
                                            variant="outlined"
                                            required
                                            fullWidth
                                            name="senha"
                                            label="Senha"
                                            type="password"
                                            id="senha"
                                            helperText="A senha deve conter no mínimo 6 caracteres."
                                            value={this.state.senha}
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <TextField
                                            variant="outlined"
                                            required
                                            fullWidth
                                            name="confirmasenha"
                                            label="Confirmação de senha"
                                            type="password"
                                            id="confirmasenha"
                                            value={this.state.confirmasenha}
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <Button
                                            fullWidth
                                            variant={this.state.buttonStyle}
                                            color="secondary"
                                            className={classes.submit}
                                            onClick={this.regulamento}
                                        >
                                            Termos de uso
                                        </Button>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <FormControlLabel
                                            control={<Checkbox checked={this.state.checkButton} name="termos" onChange={this.handleInputChange} color="primary" />}
                                            label="Declaro que li os Termos de Uso e CONCORDO."
                                        />
                                    </Grid>
                                </Grid>
                                <Button
                                    type="submit"
                                    fullWidth
                                    variant="contained"
                                    color="primary"
                                    className={classes.submit}
                                    disabled={this.state.loadingForm}
                                >
                                    Cadastrar
          </Button>
                            </form>

                            <div className={classes.forgotPasswordLink}>
                                <Link to='/login'>Já é cadastrado? Faça login.</Link>
                            </div>

                        </div>
                    </Container>
                </div>
                <BrowserView>
                    <Footer />
                </BrowserView>

                <Dialog onClose={this.handleClose} aria-labelledby="regulamento" open={this.state.open}>
                    <DialogTitle id="regulamento" onClose={this.handleClose}>
                        Termos de Uso
        </DialogTitle>
                    <DialogContent dividers>
                        <Typography gutterBottom style={{whiteSpace: 'pre-wrap'}}>
                            {this.state.regulamento}
                        </Typography>
                    </DialogContent>
                    <DialogActions>
                        <Button onClick={this.handleClose} color="primary">
                            Confirmar
          </Button>
                    </DialogActions>
                </Dialog>

                <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                    <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                        {this.state.alertMessage}
                    </Alert>
                </Snackbar>

            </ThemeProvider>
        </div>
        );
    }
}

Register.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Register);
