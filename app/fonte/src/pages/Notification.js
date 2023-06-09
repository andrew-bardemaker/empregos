import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, Button, Breadcrumbs } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    BrowserView,
} from "react-device-detect";

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


function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

class Notification extends Component {
    state = {
        notificacao: [],
        menu: false,
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        redirect:'',
    }

    componentDidMount() {
        var params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.notificacaoInterna(this, usuario.id, token, params.id)
    }
    responseNotificacaoInterna(response) {
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
            this.setState({ notificacao: response })
        }
    }
    render() {
        const { classes } = this.props;
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return <div className={classes.root} id="top">
            <ThemeProvider theme={theme}>

                <AppMenuLogged />

                <main>
                    <div className={classes.topSpace} />

                    <Container maxWidth="lg" className={classes.container}>
                        <Link to='/notificacoes'>
                            <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                                Voltar
              </Button>
                        </Link>
                    </Container>

                    <Container maxWidth="lg" className={classes.container}>
                        <Breadcrumbs aria-label="breadcrumb">
                            <Link to='/home' >
                                Home
                            </Link>
                            <Link to="/notificacoes">
                                Notificações
                            </Link>
                            <Link to={"/notificacao" + this.state.notificacao.id}>
                                {this.state.notificacao.titulo}
                            </Link>
                        </Breadcrumbs>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid item xs={12} sm={12} align="center">
                            <img src={this.state.notificacao.imagem} className="w-100"/>
                        </Grid>
                        <Grid item container direction="column" spacing={2}>
                            <Grid item>
                                <Typography component="h1" variant="h6" align="center" className={classes.title} paragraph>
                                    {this.state.notificacao.titulo}
                                </Typography>
                                <Typography variant="body2" align="center" gutterBottom>
                                    {this.state.notificacao.texto}
                                </Typography>
                            </Grid>
                        </Grid>
                    </Container>

                    <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                        <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                            {this.state.alertMessage}
                        </Alert>
                    </Snackbar>
                </main>

                <BrowserView>
                    <Footer />
                </BrowserView>
            </ThemeProvider>
        </div >
    }
}

Notification.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Notification);