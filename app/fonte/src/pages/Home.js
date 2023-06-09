
import React from 'react';
import { Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Button, Slide, Grid, Snackbar, Box, Paper, Stepper, StepLabel, Step } from '@material-ui/core';
import PropTypes from 'prop-types';
import AppMenu from '../components/Menu';
import AppMenuLogged from '../components/MenuUser';
import Footer from '../components/Footer';
import ReactTimeout from 'react-timeout';
import Services from "../Services";
import MuiAlert from '@material-ui/lab/Alert';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import 'react-awesome-slider/dist/styles.css';
// import AutoplaySlider from 'react-awesome-slider/src/hoc/autoplay/index.js'
import {
    BrowserView,
} from "react-device-detect";
import { If } from 'react-if';

import PaymentForm from './PaymentForm';
import Review from './Review';
import SenderForm from './SenderForm';
import RecipientForm from './RecipientForm';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheck, faChevronRight } from '@fortawesome/free-solid-svg-icons';

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});

function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

const theme = createTheme({
    palette: {
        primary: {
            light: '#ff8a00',
            main: '#ff8a00',
            dark: '#ff8a00',
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

class Home extends React.Component {

    state = {
        nome: '',
        email: '',
        error: '',
        redirect: '',
        action: 'criar',
        openmodal: false,
        redirect: '',
        logged: false,
        produtos: [],
        categorias: [],
        banners: [],
        funcionamento: [],
        activeStep: 0
    }

    componentDidMount() {
        const position = navigator.geolocation.getCurrentPosition(this.onSuccess);

        document.getElementById("top").scroll(0, 0);

        let driver = localStorage.getItem('driver') || '';

        if (driver !== '') {
            this.setState({ redirect: 'homeentregador' })
            return
        }

        let confirm = window.localStorage.getItem('confirm') || '';
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        if (usuario !== '' && token !== '') {
            this.setState({ logged: true });

            if (window.cordova && usuario.status_onesignal === '2') {
                this.oneSignalPush();
            }
        }

        if (confirm === '') {
            this.setState({ openmodal: true });
        }


        Services.banners(this);
    }

    oneSignalPush = () => {
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

    notificationOpenedCallback = (jsonData) => {
        console.log('notificationOpenedCallback: ' + JSON.stringify(jsonData));
    }

    onSuccess = (position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.geolocalizacao(this, usuario.id, token, latitude, longitude)
    };

    handleClose = () => {
        this.setState({ openmodal: false })
        window.localStorage.setItem('confirm', '1');
    }

    responseProdutos(response) {
        if (response.success === 'true') {
            this.setState({ produtos: response.produtos });
        }
        else {
            return
        }
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })

        if (this.state.action === 'add') {
            this.setState({ redirect: 'sacola' })
            return
        }

        window.location.reload();
    }

    responseBanners = (response) => {
        if (response.success === 'true') {
            this.setState({ banners: response.banners })
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    getStepContent(step) {
        switch (step) {
            case 0:
                return <SenderForm />;
            case 1:
                return <RecipientForm />;
            case 2:
                return <Review />;
            case 3:
                return <PaymentForm />;
            default:
                throw new Error('Unknown step');
        }
    }

    handleNext() {
        this.setState({ activeStep: this.state.activeStep + 1 });
    };

    handleBack() {
        this.setState({ activeStep: this.state.activeStep - 1 });
    };

    render() {
        const { classes } = this.props;
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }

        const steps = ['Informações Remetente', 'Informações Destinatário', 'Revisão Encomenda', 'Forma de Pagamento',];

        return (
            <div className={classes.root} id="top">
                <ThemeProvider theme={theme}>
                    <If condition={this.state.logged === false}>
                        <AppMenu />
                    </If>
                    <If condition={this.state.logged === true}>
                        <AppMenuLogged />
                    </If>
                    <main>
                        <div className={classes.topSpace} />

                        <Container maxWidth="lg" className={classes.container}>
                            <Container component="main">
                                <Box sx={{ display: 'flex', justifyContent: 'center' }}>
                                    <Paper variant="outlined" sx={{ p: { xs: 2, md: 3 } }}>
                                        <Box sx={{
                                            paddingTop: {
                                                xs: 2,
                                                sm: 4
                                            }
                                        }}>
                                            <Typography component="h1" variant="h5" align="center" sx={{
                                                fontSize: 80
                                            }}>
                                                Cadastre sua entrega
                                            </Typography>
                                            <Typography component="p" align="center">
                                                Sua encomenda entregue com segurança.
                                            </Typography>
                                            <Stepper alternativeLabel activeStep={this.state.activeStep} sx={{ pt: 3, pb: 5 }}>
                                                {steps.map((label) => (
                                                    <Step key={label} sx={{ maxWidth: '1280px', width: '100%' }}>
                                                        <StepLabel>{label}</StepLabel>
                                                    </Step>
                                                ))}
                                            </Stepper>
                                        </Box>
                                        <Box sx={{ padding: 30 }}>
                                            <React.Fragment>
                                                {this.state.activeStep === steps.length ? (
                                                    <React.Fragment>
                                                        <Typography variant="h5" gutterBottom>
                                                            Obrigado por enviar sua encomenda com a Llevo!
                                                        </Typography>
                                                        <Typography variant="subtitle1">
                                                            Seu número de pedido é #2001539. Nós lhe enviamos um email de confirmação, e enviaremos também atualizações de sua encomenda.
                                                        </Typography>
                                                    </React.Fragment>
                                                ) : (
                                                    <React.Fragment>
                                                        {this.getStepContent(this.state.activeStep)}
                                                        <Box sx={{ display: 'flex', justifyContent: 'flex-end' }}>
                                                            {this.state.activeStep !== 0 && (
                                                                <Button onClick={() => this.handleBack()} sx={{ mt: 3, ml: 1 }}>
                                                                    Voltar
                                                                </Button>
                                                            )}

                                                            {this.state.activeStep === steps.length - 1 ?
                                                                <Button
                                                                    variant="contained"
                                                                    color="primary"
                                                                    onClick={() => this.handleNext()}
                                                                    endIcon={<FontAwesomeIcon icon={faCheck} />}
                                                                    sx={{ mt: 3, ml: 1 }}
                                                                >
                                                                    Efetuar pedido
                                                                </Button>
                                                                :
                                                                <Button
                                                                    variant="contained"
                                                                    onClick={() => this.handleNext()}
                                                                    endIcon={<FontAwesomeIcon icon={faChevronRight} />}
                                                                    sx={{ mt: 3, ml: 1 }}
                                                                >
                                                                    Próximo
                                                                </Button>}

                                                        </Box>
                                                    </React.Fragment>
                                                )}
                                            </React.Fragment>
                                        </Box>
                                    </Paper>
                                </Box>
                            </Container>
                        </Container>

                        <If condition={window.cordova === undefined}>
                            <Container maxWidth="lg" className={classes.container}>
                                <Grid container spacing={2} className="bg-light-gray" direction="row"
                                    justifyContent="center"
                                    alignItems="center">
                                    <Grid item xs={12} lg={6}>
                                        <Typography component="h2" variant="h6" align="center" color='primary' paragraph>
                                            Ainda não baixou o app da Llevo?
                                        </Typography>
                                        <Typography component="h3" variant="subtitle1" align="center" color='secondary' paragraph>
                                            Baixa agora e receba seu pedido em casa, com agilidade e eficiência!
                                        </Typography>
                                    </Grid>
                                </Grid>
                            </Container>
                        </If>
                        <Container maxWidth="lg">
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
                </ThemeProvider>
            </div>
        )
    }
}

Home.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default ReactTimeout(withStyles(useStyles)(Home));