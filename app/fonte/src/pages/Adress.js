
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, Button, Slide, Breadcrumbs } from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogTitle, DialogContentText } from '@material-ui/core';
import { Card, CardActionArea, CardActions, CardContent } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import Add from '@material-ui/icons/Add';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from '@fortawesome/free-solid-svg-icons'
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

class Adress extends React.Component {

  state = {

    openAlert: false,
    alertMessage: '',
    alertStatus: 'info',
    openmodal: false,
    enderecos: [],
    deleteAdress: [],
    openFormAdress: false,
    redirect: '',
  }

  componentDidMount() {
    let usuario = JSON.parse(localStorage.getItem('user')) || '';
    let token = JSON.parse(localStorage.getItem('token')) || '';

    Services.usuariosEndereco(this, usuario.id, token)
  }

  responseUsuariosEndereco(response) {
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
      this.setState({ enderecos: response.enderecos })
    }
  }

  handleCloseAlert = () => {
    this.setState({ openAlert: false })
    this.setState({ alertMessage: '' })
    this.setState({ alertStatus: '' })
    window.location.reload();
  }

  deleteAdress = (e) => {
    this.setState({ deleteAdress: e })
    this.setState({ openmodal: true })
  }

  handleClose = () => {
    this.setState({ openmodal: false })
    window.location.reload();
  }

  deleteAdressConfirm = () => {
    let usuario = JSON.parse(localStorage.getItem('user')) || '';
    let token = JSON.parse(localStorage.getItem('token')) || '';
    let deleteAdress = this.state.deleteAdress
    Services.deleteEndereco(this, usuario.id, token, deleteAdress)
  }

  responseDeleteEndereco(response) {
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
      this.setState({ openAlert: true })
      this.setState({ alertMessage: "Tudo certo! O endereço foi excluído com sucesso!" })
      this.setState({ alertStatus: 'success' })
      return
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
              <Link to="/enderecos">
                Meus Endereços
                            </Link>
            </Breadcrumbs>
          </Container>

          <Container maxWidth="md" className={classes.container}>
            <Grid container spacing={2}>
              <Grid item xs={12} sm={12} align="center">
                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                  Endereços
                                    </Typography>
              </Grid>

              <Grid item lg={4}></Grid>

              <Grid item xs={12} lg={4}>
                <Link to={'/cadastroendereco'}><Button
                  color="primary"
                  variant="contained"
                  className="w-100"
                  endIcon={<Add />}>Cadastrar novo endereço</Button></Link>
              </Grid>

              <Grid item lg={4}></Grid>


              {this.state.enderecos.map((enderecos) => {
                return <Grid item xs={12} md={12} key={enderecos.id}><Card raised={true} className={classes.root} >
                  <CardActionArea>
                    <CardContent>
                      <Typography gutterBottom variant="h5" component="h2" color="secondary">
                        {enderecos.identificador}
                      </Typography>
                      <Typography variant="body2" color="secondary" component="p">
                        {enderecos.endereco}, {enderecos.numero} - {enderecos.cidade}
                      </Typography>
                    </CardContent>
                  </CardActionArea>
                  <CardActions>
                    <Button size="medium" color="secondary" endIcon={<FontAwesomeIcon size="xs" icon={faTrash} onClick={() => this.deleteAdress(enderecos.id)} />}>
                      Excluir endereço
                      </Button>
                  </CardActions>
                </Card></Grid>
              })}

            </Grid>
          </Container>

          <Dialog
            open={this.state.openmodal}
            TransitionComponent={Transition}
            keepMounted
            aria-labelledby="alert-dialog-title"
            aria-describedby="alert-dialog-description"
          >
            <DialogTitle id="alert-dialog-title">
              <Typography component="h2" variant="h5" align="center" color="primary">
                Exclusão de endereço
                    </Typography>
            </DialogTitle>
            <DialogContent>
              <DialogContentText id="alert-dialog-description">
                <Typography component="p" variant="body1" align="center" color="secondary">
                  Tem certeza que deseja excluir este endereço?
                </Typography>
              </DialogContentText>
            </DialogContent>
            <DialogActions >
              <Button color="secondary" variant="contained" onClick={this.handleClose}>
                Cancelar
          </Button>
              <Button color="primary" variant="contained" onClick={this.deleteAdressConfirm}>
                Confirmar
                        </Button>
            </DialogActions>
          </Dialog>

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

Adress.propTypes = {
  classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Adress);