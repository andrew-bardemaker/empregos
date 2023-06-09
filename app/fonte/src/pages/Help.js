
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types';
import { Grid, Snackbar, TextField, Button, Avatar, Container, Typography, CssBaseline, TextareaAutosize, Box } from '@material-ui/core';
import { withStyles } from '@material-ui/core/styles';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import AppMenu from '../components/Menu';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import { useStyles } from '../assets/estilos/pages/login';
import MuiAlert from '@material-ui/lab/Alert';
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import {
    BrowserView,
    MobileView,
} from "react-device-detect";
import If from 'react-if'
import { logoBranca, logoBrancaTransparente, logoLaranja } from '../assets/images/logo';

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

class Help extends React.Component {

    state = {
        error: '',
        openmodal: false,
        buttonStyle: 'outlined',
        nome: "",
        email: "",
        msg: "",
        telefone: "",
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        redirect: '',
        logged: false,
        loadingForm: false,
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0);
        var usuario = JSON.parse(localStorage.getItem('user')) || '';
        var token = JSON.parse(localStorage.getItem('token')) || '';
        if (usuario !== '' && token !== '') {
            this.setState({ logged: true });
        }
    }


    handleSubmit = e => {
        this.setState({ loadingForm: true })

        e.preventDefault();

        const values = [{
            nome: this.state.nome,
            email: this.state.email,
            telefone: this.state.telefone,
            msg: this.state.msg,
        }];

        Services.ajuda(this, values[0]);
    }

    handleInputChange = (event) => {
        if (event.target.name === 'nome') {
            this.setState({ nome: event.target.value })
        }
        if (event.target.name === 'email') {
            this.setState({ email: event.target.value })
        }
        if (event.target.name === 'telefone') {
            this.setState({ telefone: event.target.value })
        }
        if (event.target.name === 'msg') {
            this.setState({ msg: event.target.value })
        }
    }

    responseAjuda(response) {
        this.setState({ loadingForm: false })
        if (response.error === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Tudo certo! Sua mensagem foi enviada e em breve retornaremos!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    render() {
        const { classes } = this.props;
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return (<div>
            <ThemeProvider theme={theme}>
                <div className={classes.root} >

                    <If condition={this.state.logged === false}>
                        <AppMenu />
                    </If>
                    <If condition={this.state.logged === true}>
                        <AppMenuLogged />
                    </If>

                    <div className={classes.topSpace} />

                    <Container maxWidth="lg" className={classes.container}>
                        <Link to='/home'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
                            </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="sm" className={classes.formLogin}>
                        <CssBaseline />
                        <div className={classes.paper}>

                            <Box className="logoAvatar">
                                <img src={logoLaranja} />
                            </Box>

                            <Typography component="h1" variant="h6" align="center" className={classes.title} paragraph>
                                AJUDA
                            </Typography>

                            <Typography component="h2" variant="subtitle1" align="center" className={classes.titleSmall} paragraph>
                                Precisando de ajuda? Fale Conosco:
                            </Typography>

                            <form className={classes.form} onSubmit={this.handleSubmit}>
                                <Grid container spacing={2}>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="nome"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            id="nome"
                                            label="Nome"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="email"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            id="email"
                                            label="E-mail"
                                            color="secondary"
                                            type="email"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="telefone"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            id="telefone"
                                            label="Telefone"
                                            color="secondary"
                                            type="telefone"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="msg"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            multiline
                                            rows={4}
                                            id="msg"
                                            label="Mensagem"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                </Grid>
                                <Button
                                    type="submit"
                                    fullWidth
                                    variant="contained"
                                    color="secondary"
                                    className={classes.submit}
                                    disabled={this.state.loadingForm}
                                    onSubmit={this.handleSubmit}
                                >
                                    Enviar
                                </Button>
                            </form>

                        </div>

                    </Container>
                </div>
                <BrowserView>
                    <Footer />
                </BrowserView>

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

Help.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Help);
