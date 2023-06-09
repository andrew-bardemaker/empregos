
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import {
    Container, Typography, Grid, Snackbar,
    Button, Slide, Breadcrumbs, Badge, LinearProgress
} from '@material-ui/core';
import { Card, CardActionArea, CardActions, CardContent, CardMedia } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import Add from '@material-ui/icons/Add';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons'
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    BrowserView,
} from "react-device-detect";
import { If } from 'react-if';

function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

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

class Calls extends React.Component {

    state = {

        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        openmodal: false,
        chamados: [],
        redirect: '',
    }

    componentDidMount() {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.chamados(this, usuario.id, token)
    }

    responseChamados(response) {
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
            this.setState({ chamados: response.chamados })
        }
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    cancelOrder = (e) => {
        this.setState({ deleteAdress: e })
        this.setState({ openmodal: true })
    }

    handleClose = () => {
        this.setState({ openmodal: false })
        window.location.reload();
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
                        <Link to='/home'>
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
                            <Link to="/chamados">
                                Chamados
                            </Link>
                        </Breadcrumbs>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} align="center">
                                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                                    Chamados
                                    </Typography>
                            </Grid>

                            <Grid item lg={4}></Grid>

                            <Grid item xs={12} lg={4}>
                                <Link to={'/novochamado'}><Button
                                    color="primary"
                                    variant="contained"
                                    className="w-100"
                                    endIcon={<Add />}>Novo Chamado</Button></Link>
                            </Grid>

                            <Grid item lg={4}></Grid>


                            {this.state.chamados.map((chamados) => {
                                return <Grid item xs={12} md={12} key={chamados.id}><Link to={"/chamado/" + chamados.id}><Card raised={true} className={classes.root} >
                                    <CardActionArea>
                                        <CardContent>
                                            <Typography gutterBottom variant="h5" component="h2" color="secondary">
                                                Status do chamado: {chamados.status}
                                            </Typography>
                                            <Typography variant="body2" color="secondary" component="p">
                                                Identificador: #{chamados.id}
                                            </Typography>
                                            <Typography variant="body2" color="secondary" component="p">
                                                Assunto: {chamados.assunto}
                                            </Typography>
                                            <Typography variant="body2" color="secondary" component="p">
                                                Última interação: {chamados.ultima_interacao}
                                            </Typography>
                                        </CardContent>
                                    </CardActionArea>
                                    <CardActions>
                                        <Link to={"/chamado/" + chamados.id}><Button variant="contained" size="medium" color="primary">
                                            Detalhes do Chamado
                      </Button></Link>
                                    </CardActions>
                                </Card></Link></Grid>
                            })}

                            <If condition={this.state.chamados.length === 0}>
                                <Typography variant="body2" color="secondary" component="p" paragraph>
                                    Ainda não há chamados para exibir.
                                </Typography>
                            </If>

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

Calls.propTypes = {
                classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Calls);