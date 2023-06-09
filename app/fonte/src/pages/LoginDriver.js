
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

class LoginDriver extends React.Component {

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

    Services.loginEntregadores(this, values[0]);
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
      window.localStorage.removeItem('user');
      localStorage.setItem('user', JSON.stringify(response.user));
      localStorage.setItem('driver', "ok");
      this.setState({redirect: 'homeentregador'})
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
            <Link to='/home'>
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
                Login - Entregadores
        </Typography>

              <Typography component="h2" variant="subtitle1" align="center" className={classes.titleSmall} paragraph>
                Tchê! Seja bem-vindo ao Llevo<br /> Faça o login para receber pedidos:
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
                      label="Login"
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

            </div>

            <Snackbar open={this.state.open_message} onClose={this.handleClose}>
              <MuiAlert elevation={6} variant="filled" onClose={this.handleClose} severity={this.state.modal_type}>
                {this.state.error}
              </MuiAlert>
            </Snackbar>

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

LoginDriver.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(LoginDriver);
