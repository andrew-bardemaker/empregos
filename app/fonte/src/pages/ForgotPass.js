
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types';
import { Grid, Snackbar, TextField, Button, Avatar, Container, Typography, CssBaseline } from '@material-ui/core';
import { withStyles } from '@material-ui/core/styles';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import AppMenu from '../components/Menu';
import Services from "../Services";
import { useStyles } from '../assets/estilos/pages/login';
import MuiAlert from '@material-ui/lab/Alert';
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import {
    BrowserView,
    MobileView,
} from "react-device-detect";
import { logoBranca } from '../assets/images/logo';

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

class ForgotPass extends React.Component {

    state = {
        email: '',
        error: '',
        openmodal: false,
        buttonStyle: 'outlined',
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        loadingForm: false,
        redirect: '',
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0)
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

    handleSubmit = e => {
        this.setState({ loadingForm: true })

        e.preventDefault();

        let email = this.state.auten;

        Services.recuperaSenha(this, email);
    }

    handleInputChange = (event) => {
        if (event.target.name === 'auten') {
            this.setState({ auten: event.target.value })
        }
    }

    responseRecuperaSenha(response) {
        this.setState({ loadingForm: false })
        if (response.error === 'true') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Tudo certo! Aguarde, em breve tu receberás um e-mail com as instruções!" })
            this.setState({ alertStatus: 'success' })
            return
        }
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
                        <Link to='/login'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
                            </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="sm" className={classes.formLogin}>
                        <CssBaseline />
                        <div className={classes.paper}>

                            <Avatar className="logoAvatar">
                                <img src={logoBranca} />
                            </Avatar>

                            <Typography component="h1" variant="h6" align="center" className={classes.title} paragraph>
                                Recuperação de Senha
                            </Typography>

                            <Typography component="h2" variant="subtitle1" align="center" className={classes.titleSmall} paragraph>
                                Enviaremos as instruções via e-mail para recuperar sua senha:
                            </Typography>

                            <form className={classes.form} onSubmit={this.handleSubmit}>
                                <Grid container spacing={2}>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="auten"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            id="auten"
                                            label="E-mail"
                                            color="secondary"
                                            onChange={this.handleInputChange}
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
                                    Recuperar Senha
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

ForgotPass.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(ForgotPass);
