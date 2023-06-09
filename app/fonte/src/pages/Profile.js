import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import Avatar from 'react-avatar-edit'
import Services from "../Services";
import { makeStyles, withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/profile';
import { CssBaseline, Container, Typography, Button, Grid, Snackbar, ButtonGroup, TextField, Breadcrumbs } from '@material-ui/core';
import { Dialog, DialogActions, DialogContent } from '@material-ui/core';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCamera, faTrash, faUpload, faEnvelope } from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faTwitter, faWhatsapp } from '@fortawesome/free-brands-svg-icons'
import PropTypes from 'prop-types';
import AppMenuLogged from '../components/MenuUser';
import Footer from '../components/Footer';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import MuiAlert from '@material-ui/lab/Alert';
import {
    BrowserView
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

class Profile extends React.Component {

    state = {
        nome: '',
        email: '',
        error: '',
        openmodal: false,
        open: false,
        redirect: '',
        produtos: [],
        categorias: [],
        marcas: [],
        // paginas: 1,
        visible: false,
        alteracao: false,
        values: [],
        nome: '',
        email: '',
        telefone: '',
        confirmasenha:'',
        senha:'',
        noprodutos: localStorage.getItem('noprodutos') || '',
        loadingCamera: false,
        loadingCamera2: false,
        loadingForm: false,
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0)
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        this.setState({ preview: usuario.avatar })
        Services.usuarios(this, usuario.id, token);
    }

    showModal = () => {
        this.setState({
            visible: true,
        });
    };

    handleOk = () => {
        this.setState({
            visible: false,
        });
        this.setState({
            alteracao: true,
        });
        let arq = localStorage.getItem('base64');
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        Services.alteraAvatar(this, usuario.id, token, arq);

    };

    handleSubmit = e => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        this.setState({ loadingForm: true });
        e.preventDefault();
        // let token = JSON.parse(localStorage.getItem('token')) || '';
        let values = {
            nome: this.state.nome,
            email: this.state.email,
            telefone: this.state.telefone,
            senha: this.state.senha,
            confirma_senha: this.state.confirmasenha,
            token: JSON.parse(localStorage.getItem('token')) || '',
        };
        Services.usuariosAtualiza(this, usuario.id, values);
    }

    handleCancel = () => {
        this.setState({
            visible: false,
        });
        let user = JSON.parse(localStorage.getItem('user'));
        this.setState({ preview: user.avatar })
    };

    onCrop(preview) {
        // this.setState({ preview : preview })
        // console.log(preview)
        localStorage.setItem('base64', preview);
    }

    takePicture = (event) => {
        const destinationType = navigator.camera.DestinationType;
        event.preventDefault();
        navigator.camera.getPicture(this.onSuccess, this.onFail, {
            quality: 50,
            correctOrientation: true,
            allowEdit: true,
            destinationType: destinationType.DATA_URL
        });

    }

    onSuccess = (imageData) => {
        let picSrc = "data:image/jpeg;base64," + imageData;
        localStorage.setItem('base64', picSrc);
        this.handleOk();
    }

    onFail = (message) => {
        alert('Erro: ' + message);
    }

    deleteAvatar = () => {
        this.setState({ loadingCamera2: true });
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        this.setState({
            alteracao: true,
        });
        Services.deleteAvatar(this, usuario.id, token);
    }

    handleCloseAlert = () => {
        if (this.state.alertStatus === 'success') {
            window.location.reload();
            return
        }
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
    }

    handleInputChange = (event) => {
        if (event.target.name === 'nome') {
            this.setState({ nome: event.target.value })
            console.log(this.state.nome);
        }
        if (event.target.name === 'email') {
            this.setState({ email: event.target.value })
        }
        if (event.target.name === 'telefone') {
            this.setState({ telefone: event.target.value })
        }
        if (event.target.name === 'senha') {
            this.setState({ senha: event.target.value })
        }
        if (event.target.name === 'senha_confirma') {
            if (this.state.confirmasenha !== this.state.senha) {
                this.setState({ helperSenha: "As senhas não correspondem" })
            }
            else {
                this.setState({ helperSenha: "" })
            }
            this.setState({ confirmasenha: event.target.value })
        }
    }

    responseAvatar(response) {

        this.setState({ loadingCamera: false });
        this.setState({ loadingCamera2: false });

        this.setState({ response: response });
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
            let usuario = JSON.parse(localStorage.getItem('user')) || '';
            let token = JSON.parse(localStorage.getItem('token')) || '';
            Services.usuarios(this, usuario.id, token);
        }
    }

    responseUsuarios(response) {
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

        if (response.success === 'true' && this.state.alteracao === true) {
            window.localStorage.removeItem('user');
            localStorage.setItem('user', JSON.stringify(response));

            this.setState({ nome: response.nome });
            this.setState({ email: response.email });
            this.setState({ telefone: response.telefone_celular });

            this.setState({ alteracao: false })
            this.setState({ openAlert: true })
            this.setState({ alertMessage: 'Informações alteradas com sucesso!' })
            this.setState({ alertStatus: 'success' })
        }

        if (response.success === 'true' && this.state.alteracao !== true) {
            window.localStorage.removeItem('user');
            localStorage.setItem('user', JSON.stringify(response))

            this.setState({ nome: response.nome });
            this.setState({ email: response.email });
            this.setState({ telefone: response.telefone_celular });
        }
    }

    responseUsuariosAtualiza(response) {
        this.setState({ loadingForm: false })
        if (response.success === 'true') {
            this.setState({ alteracao: true })
            let usuario = JSON.parse(localStorage.getItem('user')) || '';
            let token = JSON.parse(localStorage.getItem('token')) || '';
            Services.usuarios(this, usuario.id, token);
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    render() {
        const { classes } = this.props;
        const usuario = JSON.parse(localStorage.getItem('user'));

        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return (
            <div className={classes.root} id="top">
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
                                <Link to="/perfil">
                                    Perfil
                            </Link>
                            </Breadcrumbs>
                        </Container>

                        <Container maxWidth="lg" className={classes.container}>
                            <Grid container spacing={2}
                                direction="column"
                                justifyContent="center"
                                alignItems="center">
                                <Dialog
                                    open={this.state.visible}
                                    keepMounted
                                >
                                    <DialogContent>
                                        <Avatar
                                            width={300}
                                            height={300}
                                            imageWidth={300}
                                            onCrop={this.onCrop}
                                            src={this.state.base64}
                                            label="alterar imagem"
                                        />
                                    </DialogContent>
                                    <DialogActions >
                                        <Button color="secondary" variant="contained" onClick={this.handleCancel}>
                                            Cancelar
                                        </Button>
                                        <Button color="primary" variant="contained" onClick={this.handleOk} disabled={this.state.loadingCamera}>
                                            Confirma
                                            </Button>
                                    </DialogActions>
                                </Dialog>
                                <Grid item lg={12} xs={12} spacing={2} >
                                    <img className="MuiAvatar-root MuiAvatar-circle logoAvatar" src={this.state.preview} alt="Avatar" />
                                </Grid>
                                <Grid item lg={12} xs={12} spacing={2} >
                                    <ButtonGroup size="large" color="primary" variant='contained' >
                                        <Button onClick={this.showModal}>
                                            <FontAwesomeIcon icon={faUpload} />
                                        </Button>

                                        <Button className="icon-action" onClick={this.deleteAvatar} disabled={this.state.loadingCamera2}>
                                            <FontAwesomeIcon icon={faTrash} />
                                        </Button>
                                    </ButtonGroup>
                                </Grid>
                            </Grid>
                        </Container>

                        <Container maxWidth="sm" className={classes.profile}>
                            <form className={classes.form} onSubmit={this.handleSubmit}>
                                <Grid container spacing={2}>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            value={this.state.nome}
                                            name="nome"
                                            variant="outlined"
                                            fullWidth
                                            id="nome"
                                            label="Nome"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            value={this.state.email}
                                            name="email"
                                            variant="outlined"
                                            fullWidth
                                            id="email"
                                            label="E-mail"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            value={this.state.telefone}
                                            name="telefone"
                                            variant="outlined"
                                            fullWidth
                                            id="telefone"
                                            label="Telefone"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="senha"
                                            variant="outlined"
                                            fullWidth
                                            id="senha"
                                            label="Senha"
                                            color="secondary"
                                            type="password"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>
                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="senha_confirma"
                                            variant="outlined"
                                            fullWidth
                                            id="senha_confirma"
                                            label="Confirmação de senha"
                                            color="secondary"
                                            type="password"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>

                                    <Grid item xs={12} sm={12}>
                                        <Button
                                            type="submit"
                                            fullWidth
                                            variant="contained"
                                            color="secondary"
                                            disabled={this.state.loadingForm}
                                            className={classes.submit}
                                            onSubmit={this.handleSubmit}
                                        >
                                            Alterar informações
                                    </Button>
                                    </Grid>
                                </Grid>
                            </form>
                        </Container>

                        <Container maxWidth="lg" className={classes.comingSoon}>
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
    };
}


Profile.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Profile);