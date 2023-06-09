
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types';
import { Grid, Snackbar, TextField, Button, Avatar, Container, Typography, CssBaseline, Box } from '@material-ui/core';
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

class Login extends React.Component {

  state = {
    nome: '',
    email: '',
    error: '',
    openmodal: false,
    buttonStyle: 'outlined',
    auten: "",
    senha: "",
    openAlert: false,
    alertMessage: '',
    alertStatus: 'info',
    redirect: '',
    loadingForm: false,
    redirect_aux: '',
  }

  componentDidMount() {
    document.getElementById("top").scroll(0, 0)
  }

  handleCloseAlert = () => {
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

    const values = [{
      auten: this.state.auten,
      senha: this.state.senha,
    }];

    Services.login(this, values[0]);
  }

  handleInputChange = (event) => {
    if (event.target.name === 'auten') {
      this.setState({ auten: event.target.value })
    }
    else if (event.target.name === 'senha') {
      this.setState({ senha: event.target.value })
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

          <Container maxWidth="sm" className={classes.formLogin}>
            <CssBaseline />
            <div className={classes.paper}>

              <Box className="logoAvatar">
                <img src="https://dedstudio.com.br/dev/bejobs/img/logoLaranja.png" />
              </Box>

              <Typography component="h2" variant="subtitle1" align="center" className={classes.titleSmall} paragraph>
                Faça login ou cadastre-se para continuar:
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
                      label="E-mail ou CPF"
                      color="secondary"
                      onChange={this.handleInputChange}
                    />
                  </Grid>
                  <Grid item xs={12} sm={12}>
                    <TextField
                      name="senha"
                      variant="outlined"
                      required
                      fullWidth
                      id="senha"
                      label="Senha"
                      color="secondary"
                      type="password"
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
                  Entrar
                </Button>
              </form>

              <Button
                fullWidth
                variant="contained"
                color="primary"
                className={classes.submit}
              >
                <Link to="/cadastro"> Quero me cadastrar </Link>
              </Button>

              <div className={classes.forgotPasswordLink}><Link to="/esqueciasenha">Esqueceu sua senha?</Link></div>
              <div className={classes.forgotPasswordLink}><Link to="/loginentregador">Sou um entregador Llevo</Link></div>
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

Login.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Login);
