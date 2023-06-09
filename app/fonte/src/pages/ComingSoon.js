
import React from 'react';
import axios from 'axios';
import PropTypes from 'prop-types';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import TextField from '@material-ui/core/TextField';
import Link from '@material-ui/core/Link';
import Grid from '@material-ui/core/Grid';
import Box from '@material-ui/core/Box';
import { withStyles } from '@material-ui/core/styles';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import Typography from '@material-ui/core/Typography';
import Container from '@material-ui/core/Container';
import Services from "../Services";
import { useStyles } from '../assets/estilos/pages/comingsoon';
import Snackbar from '@material-ui/core/Snackbar';
import MuiAlert from '@material-ui/lab/Alert';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBeer } from '@fortawesome/free-solid-svg-icons'
import { logoBranca } from '../assets/images/logo';

const theme = createTheme({
  palette: {
    primary: {
      light: '#fff',
      main: '#fff',
      dark: '#fff',
      contrastText: '#000',
    },
    secondary: {
      light: '#444444',
      main: '#444444',
      dark: '#444444',
      contrastText: '#000',
    },
  },
});

class ComingSoon extends React.Component {

  state = {
    nome: '',
    email: '',
    error: '',
    open_message: false,
    openmodal: false,
  }

  onChangeNome = e => {
    console.log(e.target.value);
    this.setState({ nome: e.target.value })
  }

  onChangeEmail = e => {
    console.log(e.target.value);
    this.setState({ email: e.target.value })
  }

  handleSubmit = e => {

    e.preventDefault();
    let formData = new FormData();    //formdata object

    formData.append('name', this.state.nome);   //append the values with key, value pair
    formData.append('email', this.state.email);
    formData.append('codigo', 3483);
    formData.append('grupos[4147]', 4147);
    Services.mailing(this, formData);
  }

  mailingSuccess() {
    this.setState({ error: "E-mail cadastrado com sucesso!" });
    this.setState({ open_message: true })
    this.setState({ modal_type: 'success' })
  }

  mailingError() {
    this.setState({ error: "Falha no cadastro do e-mail, tente novamente!" });
    this.setState({ open_message: true })
    this.setState({ modal_type: 'error' })
  }
  render() {
    const { classes } = this.props;
    return (
      <Container component="main" maxWidth="xs" className={classes.comingSoon}>
        <ThemeProvider theme={theme}>
          <CssBaseline />
          <div className={classes.paper}>

            <Avatar className={classes.avatar}>
              <img style={{ width: '100%' }} src={logoBranca} />
            </Avatar>

            <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>

            </Typography>

            <Typography component="h2" variant="h5" align="center" className={classes.title} paragraph>
              Llevo
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
                    autoFocus
                    onChange={this.onChangeNome}
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
                    onChange={this.onChangeEmail}
                    autoFocus
                  />
                </Grid>
              </Grid>
              <Button
                type="submit"
                fullWidth
                variant="contained"
                color="secondary"
                className={classes.submit}
                onSubmit={this.handleSubmit}
              >
                Inscreva-se para receber novidades
          </Button>

            </form>
          </div>

          <Snackbar open={this.state.open_message} onClose={this.handleClose}>
            <MuiAlert elevation={6} variant="filled" onClose={this.handleClose} severity={this.state.modal_type}>
              {this.state.error}
            </MuiAlert>
          </Snackbar>

          <Box mt={5}>
            <Typography component="p" variant="subtitle1" align="center" className={classes.rodape} paragraph>
            &copy; 2022 Llevo - Desenvolvido por <Link color="secondary" href="https://dedstudio.com.br/">DeD Studio</Link>.<br/>
        </Typography>
          </Box>
        </ThemeProvider>
      </Container>
    );
  }
}

ComingSoon.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(ComingSoon);
