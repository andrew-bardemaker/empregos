import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import Avatar from 'react-avatar-edit'
import Services from "../Services";
import { makeStyles, withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/profile';
import {
    CssBaseline, Container, Typography, Button, Grid, Snackbar, Slide, Breadcrumbs,
    TextField, InputLabel, Select, MenuItem, FormControl
} from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogTitle, DialogContentText } from '@material-ui/core';
import PropTypes from 'prop-types';
import Bairros from '../components/Bairros';
import AppMenuLogged from '../components/MenuUser';
import Footer from '../components/Footer';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import MuiAlert from '@material-ui/lab/Alert';
import {
    BrowserView
} from "react-device-detect";
import { If } from 'react-if';
import Geocode from "react-geocode";

function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

Geocode.setRegion("pt-br");

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});

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

class NewAdressCheckout extends React.Component {
    constructor(props) {
        super(props);
        this.webCep = this.webCep.bind(this);
    }

    state = {
        openmodal: false,
        openmodal2: false,
        redirect: '',
        visible: false,
        alteracao: false,
        values: [],
        bairros1: [],
        bairros2: [],
        lat: '',
        long: '',
        cep: '',
        bairro: '',
        cidade: '',
        numero: '',
        uf: '',
        endereco: '',
        complemento: '',
        identificador: '',
        loadingForm: false,
        read_only: false,
    }

    componentDidMount() {
        Services.bairros(this);
    }

    handleSubmit = e => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        this.setState({ loadingForm: true });
        e.preventDefault();
        let values = [{
            cep: this.state.cep,
            endereco: this.state.endereco,
            numero: this.state.numero,
            complemento: this.state.complemento,
            identificador: this.state.identificador,
            bairro: this.state.bairro,
            cidade: this.state.cidade,
            uf: this.state.uf,
            lat: this.state.lat,
            long: this.state.long,
            token: token,
        }];

        Services.cadastroEndereco(this, usuario.id, values[0]);
    }

    handleClose = () => {
        this.setState({ openmodal: false })
        this.setState({ openmodal2: false })
    }

    handleInputChange = (event) => {
        if (event.target.name === 'bairro') {
            this.setState({ bairro: event.target.value })

            if (event.target.value === "outros") {
                this.setState({ openmodal: true })
            }
        }
        if (event.target.name === 'cep') {
            this.setState({ cep: event.target.value })
        }
        if (event.target.name === 'cidade') {
            this.setState({ cidade: event.target.value })

            if (event.target.value === "outros") {
                this.setState({ openmodal: true })
            }
        }
        if (event.target.name === 'endereco') {
            if (this.state.cep === '') {
                this.verificaCEP();
                return
            }
            this.setState({ endereco: event.target.value })
        }
        if (event.target.name === 'uf') {
            this.setState({ endereco: event.target.value })
        }
        if (event.target.name === 'numero') {
            this.setState({ numero: event.target.value })
        }
        if (event.target.name === 'complemento') {
            this.setState({ complemento: event.target.value })
        }
        if (event.target.name === 'identificador') {
            this.setState({ identificador: event.target.value })
        }
        if (event.target.name === 'latitude') {
            this.setState({ latitude: event.target.value })
        }
        if (event.target.name === 'longitude') {
            this.setState({ longitude: event.target.value })
        }

    }

    responseBairros(response) {
        if (response.success === 'true') {
            this.setState({ bairros1: response.bairros })
            this.setState({ bairros2: response.bairros })
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    webCep = e => {
        // this.setState({ read_only: false })
        this.setState({
            cep: e.target.value
        });
        Services.webCep(this, e.target.value)
    }

    responseWebCep = (retorno) => {

        if (retorno.resultado === '1') {
            this.setState({ endereco: retorno.logradouro })
            this.setState({ uf: retorno.uf })
            // this.setState({ read_only: true })
        }

        if (retorno.resultado === '2') {
            this.setState({ uf: retorno.uf })
            // this.setState({ read_only: false })
        }

        if (retorno.resultado === '0') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa! " + retorno.resultado_txt })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    handleCloseAlert = () => {
        if (this.state.alertStatus === "success") {
            this.setState({ redirect: 'checkout' })
            return
        }
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
    }

    responseCadastroEndereco(response) {
        this.setState({ loadingForm: false });
        this.setState({ openmodal2: false })

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

        if (response.success === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Tudo certo! Seu novo endereço foi cadastrado com sucesso!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    openModal = e => {
        this.setState({ openmodal2: true })
    }

    verificaCEP = () => {
        if (this.state.cep === '') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa! Preencha o CEP primeiro!" })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    render() {
        const { classes } = this.props;
        const usuario = JSON.parse(localStorage.getItem('user'));

        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return (
            <div className={classes.root} id="top">
                <ThemeProvider theme={theme}>
                    <AppMenuLogged />

                    <main>
                        <div className={classes.topSpace} />

                        <Container maxWidth="lg" className={classes.container}>
                            <Link to='/enderecos'>
                                <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                    Voltar
                                </Button>
                            </Link>
                        </Container>

                        <Grid container>
                            <Grid item xs={12} lg={12}>
                                <Typography component="h2" variant="h5" align="center" className={classes.title} paragraph>
                                    Novo Endereço
                                </Typography>
                            </Grid>
                        </Grid>

                        <Container maxWidth="sm" className={classes.profile}>
                            <Grid container spacing={2}>
                                <Grid item xs={12} sm={12}>
                                    <FormControl variant="outlined" className="w-100">
                                        <InputLabel id="cidade">Cidade</InputLabel>
                                        <Select
                                            label="Cidade"
                                            onChange={this.handleInputChange}
                                            value={this.state.cidade}
                                            inputProps={{
                                                name: "cidade",
                                                id: "cidade"
                                            }}
                                            fullWidth
                                        >
                                            <MenuItem value={""}>
                                                Selecione uma cidade
                                            </MenuItem>
                                            <MenuItem value={"porto alegre"}>
                                                Porto Alegre
                                            </MenuItem>
                                            <MenuItem value={'outros'}>
                                                Outras
                                            </MenuItem>
                                        </Select>
                                    </FormControl>
                                </Grid>

                                <Grid item xs={12} sm={12}>
                                    <FormControl variant="outlined" className="w-100">
                                        <InputLabel id="bairros">Bairros</InputLabel>
                                        <Select
                                            label="Bairros"
                                            onChange={this.handleInputChange}
                                            value={this.state.bairro}
                                            inputProps={{
                                                name: "bairro",
                                                id: "bairro"
                                            }}
                                            fullWidth
                                        >
                                            <MenuItem value={""}>
                                                Selecione um bairro
                                            </MenuItem>
                                            {this.state.bairros1.map((bairro) => {
                                                return <MenuItem value={bairro.id}> {bairro.titulo}</MenuItem>
                                            })};
                                            <MenuItem value={'outros'}>
                                                Outros
                                            </MenuItem>
                                        </Select>
                                    </FormControl>
                                </Grid>
                                <Grid item xs={12} sm={12}>
                                    <TextField
                                        name="identificador"
                                        variant="outlined"
                                        fullWidth
                                        helperText="Exemplo: Casa, Trabalho"
                                        id="identificador"
                                        label="Identificador"
                                        color="secondary"
                                        onChange={this.handleInputChange}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={12}>
                                    <TextField
                                        name="cep"
                                        variant="outlined"

                                        fullWidth
                                        id="cep"
                                        label="CEP"
                                        color="secondary"
                                        type="tel"
                                        onBlur={this.webCep}
                                        onChange={this.handleInputChange}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={12}>
                                    <TextField
                                        value={this.state.endereco}
                                        name="endereco"
                                        // InputProps={{
                                        //     readOnly: this.state.read_only,
                                        // }}
                                        variant="outlined"
                                        fullWidth
                                        id="endereco"
                                        label="Endereço"
                                        color="secondary"
                                        type="text"
                                        onChange={this.handleInputChange}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={12}>
                                    <TextField
                                        name="numero"
                                        variant="outlined"
                                        fullWidth
                                        id="numero"
                                        label="Número"
                                        color="secondary"
                                        type="tel"
                                        onChange={this.handleInputChange}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={12}>
                                    <TextField
                                        name="complemento"
                                        variant="outlined"
                                        fullWidth
                                        id="complemento"
                                        label="Complemento"
                                        color="secondary"
                                        type="text"
                                        onChange={this.handleInputChange}
                                    />
                                </Grid>
                                <Grid item xs={12} sm={12}>
                                    <TextField
                                        name="uf"
                                        variant="outlined"
                                        InputProps={{
                                            readOnly: true,
                                        }}
                                        fullWidth
                                        id="uf"
                                        label="UF"
                                        color="secondary"
                                        type="text"
                                        value={this.state.uf}
                                        onClick={this.verificaCEP}
                                    />
                                </Grid>

                                <Grid item xs={12} sm={12}>
                                    <Button
                                        type="submit"
                                        fullWidth
                                        variant="contained"
                                        color="secondary"
                                        disabled={this.state.loadingForm}
                                        className={classes.submit}
                                        onClick={this.openModal}
                                    >
                                        Cadastrar Endereço
                                    </Button>
                                </Grid>
                            </Grid>
                        </Container>

                        <Container maxWidth="lg" className={classes.container}>
                            <Grid container spacing={2}>
                                <Grid item xs={12} lg={12}>
                                    <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>
                                        Bairros Atendidos
                                    </Typography>
                                </Grid>

                            </Grid>
                        </Container>

                        <Container maxWidth="lg" className={classes.comingSoon}>
                            <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                                <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                                    {this.state.alertMessage}
                                </Alert>
                            </Snackbar>
                        </Container>
                    </main>

                    <BrowserView>
                        <Footer />
                    </BrowserView>


                    <Dialog
                        open={this.state.openmodal}
                        TransitionComponent={Transition}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                    >
                        <DialogTitle id="alert-dialog-title" className={classes.ageModalTitle}>
                            <Typography component="h2" variant="h5" align="center" color="primary">
                                Bah!
                            </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <Typography variant="body1" align="center" color="secondary">
                                Infelizmente sua área não é atendida pela Llevo. Em breve estaremos chegando ai!
                            </Typography>
                        </DialogContent>
                        <DialogActions >
                            <Button color="primary" variant="contained" onClick={this.handleClose}>
                                Ok
                            </Button>
                        </DialogActions>
                    </Dialog>

                    <Dialog
                        open={this.state.openmodal2}
                        TransitionComponent={Transition}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                    >
                        <DialogTitle id="alert-dialog-title" className={classes.ageModalTitle}>
                            <Typography component="h2" variant="h5" align="center" color="primary">
                                Tchê!
                            </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <Typography component="h3" variant="body1" align="center" color="secondary">
                                Cadastrando teu endereço tu confirma que ele faz parte da nossa área de entrega! Confira os bairros abaixo: <br />
                                <Bairros />
                            </Typography>
                            <Typography component="h3" variant="body1" align="center" color="secondary">
                                Pedidos feitos fora da área de cobertura serão cancelados e estão sujeitos a cobrança tarifária.
                            </Typography>
                        </DialogContent>
                        <DialogActions >
                            <Button color="secondary" variant="contained" onClick={this.handleClose}>
                                Cancelar
                            </Button>
                            <Button color="primary" variant="contained" onClick={this.handleSubmit}>
                                Confirmar endereço
                            </Button>
                        </DialogActions>
                    </Dialog>

                </ThemeProvider>
            </div>
        )
    };
}


NewAdressCheckout.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(NewAdressCheckout);