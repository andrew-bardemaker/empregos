
import React from 'react';
import { Link } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, TextField, Badge, Button } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import SearchIcon from '@material-ui/icons/Search';
import AppMenu from '../components/Menu';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import Highlighter from "react-highlight-words"
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

class Faq extends React.Component {

  state = {

    openAlert: false,
    alertMessage: '',
    alertStatus: 'info',
    faq: [],
    logged: false,
    busca:'',
  }

  componentDidMount() {
    document.getElementById("top").scroll(0, 0);
    var usuario = JSON.parse(localStorage.getItem('user')) || '';
    var token = JSON.parse(localStorage.getItem('token')) || '';
    if (usuario !== '' && token !== '') {
      this.setState({ logged: true });
    }

    Services.faq(this, '')
  }

  onChangeBusca = (event) => {
    this.setState({ busca: event.target.value })
    Services.faq(this, event.target.value)
  }

  responseFaq(response) {
    if (response.error === 'true') {
      this.setState({ openAlert: true })
      this.setState({ alertMessage: "Opa!" + response.msg })
      this.setState({ alertStatus: 'error' })
      this.setState({ loadingForm: false })
      return
    }
    else {
      this.setState({ faq: response.perguntas })
    }
  }

  render() {
    const { classes } = this.props;
    return <div className={classes.root} id="top">
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
            <Link to='/home'>
              <Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                Voltar
              </Button>
            </Link>
          </Container>

          <Container maxWidth="md" className={classes.container}>
            <Grid container spacing={2}>
              <Grid item xs={12} sm={12} align="center">
                <Typography component="h1" variant="h6" align="center" className={classes.title} paragraph>
                  Faq
                                    </Typography>
              </Grid>

              <Grid item xs={3}></Grid>

              <Grid item xs={12} lg={6}>
                <TextField
                  fullWidth
                  onChange={this.onChangeBusca}
                  placeholder="Buscar..."
                  type="text"
                  name="busca"
                  className="textfieldFaq" />

              </Grid>

              <Grid item container direction="column" spacing={2}>
                {this.state.faq.map((faq) => {
                  return  <Grid item className="p-2em bg-light-gray w-100">
                      <Typography gutterBottom variant="h5" color="primary" className="uppercase">
                        <Highlighter
                          highlightStyle={{ backgroundColor: '#444444', color: '#fff' }}
                          searchWords={[this.state.busca.toString()]}
                          autoEscape={true}
                          textToHighlight={faq.titulo}
                        />
                      </Typography>
                      <Typography variant="body2" color="secondary" gutterBottom>
                      <Highlighter
                          highlightStyle={{ backgroundColor: '#444444', color: '#fff' }}
                          searchWords={[this.state.busca.toString()]}
                          autoEscape={true}
                          textToHighlight={faq.texto}
                        />
                      </Typography>
                    </Grid>
                })}
              </Grid>
              <If condition={this.state.faq.length === 0}>
                <Grid item xs={12} sm={12} align="center">
                  <Typography variant="body2" color="secondary" gutterBottom>
                    Não foram encontrados resultados para esta pesquisa.
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

Faq.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Faq);