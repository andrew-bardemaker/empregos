
import React from 'react';
import { Link } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, Badge, Button, Breadcrumbs } from '@material-ui/core';
import { Card, CardActionArea, CardActions, CardContent, CardMedia } from '@material-ui/core';
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
import { If } from 'react-if';

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

class Notifications extends React.Component {

  state = {

    openAlert: false,
    alertMessage: '',
    alertStatus: 'info',
    notificacoes: [],
  }

  componentDidMount() {
    let usuario = JSON.parse(localStorage.getItem('user')) || '';
    let token = JSON.parse(localStorage.getItem('token')) || '';

    Services.notificacoes(this, usuario.id, token)
  }

  responseNotificacoes(response) {
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
      this.setState({ notificacoes: response.notificacoes })
    }
  }

  handleCloseAlert = () => {
    this.setState({ openAlert: false })
    this.setState({ alertMessage: '' })
    this.setState({ alertStatus: '' })
  }

  render() {
    const { classes } = this.props;
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
              <Link to="/notificacoes">
                Notificações
                            </Link>
            </Breadcrumbs>
          </Container>

          <Container maxWidth="md" className={classes.container}>
            <Grid container spacing={2}>

              <Grid item xs={12} sm={12} align="center">
                <Typography component="h1" variant="h6" align="center" className={classes.title} paragraph>
                  Notificações
                                    </Typography>
              </Grid>

              {this.state.notificacoes.map((notificacoes) => {
                return <Grid item xs={12} md={12} key={notificacoes.id}>
                  <Link to={"/notificacao/" + notificacoes.id}><Card raised={true} className={classes.root} >
                    <CardActionArea>
                      <CardContent>
                        <Typography gutterBottom variant="h5" component="h2" color="secondary">
                          {notificacoes.titulo}
                        </Typography>
                        <Typography variant="body2" color="secondary" component="p">
                          {notificacoes.status}
                        </Typography>
                        <Typography variant="body2" color="secondary" component="p">
                          {notificacoes.data_hora_registro}
                        </Typography>
                      </CardContent>
                    </CardActionArea>
                    <CardActions>
                      <Link to={"/notificacao/" + notificacoes.id}><Button variant="contained" size="medium" color="primary">
                        Ler mensagem
                      </Button></Link>
                    </CardActions>
                  </Card></Link></Grid>

              })}

              <If condition={this.state.notificacoes.length === 0}>
                <Grid item xs={12} sm={12} align="center">
                  <Typography variant="body2" color="secondary" gutterBottom>
                    Não há notificações no momento.
                  </Typography>
                </Grid>
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
    </div>
  }
}

Notifications.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Notifications);