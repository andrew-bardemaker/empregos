import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import Avatar from 'react-avatar-edit'
import Services from "../Services";
import { makeStyles, withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/profile';
import {
    CssBaseline, Container, Typography, Button, Grid, Snackbar, Slide, Breadcrumbs,
    TextField, InputLabel, Select, MenuItem, FormControl, Input
} from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogTitle, DialogContentText } from '@material-ui/core';
import PropTypes from 'prop-types';
import Bairros from '../components/Bairros';
import AppMenuLogged from '../components/MenuUser';
import Footer from '../components/Footer';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import MuiAlert from '@material-ui/lab/Alert';
import SaveIcon from '@material-ui/icons/AddPhotoAlternate';
import {
    BrowserView
} from "react-device-detect";
import { If } from 'react-if';
import Geocode from "react-geocode";

function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

Geocode.setRegion("pt-br");

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

class NewCall extends React.Component {

    state = {
        openmodal: false,
        redirect: '',
        visible: false,
        alteracao: false,
        values: [],
        categorias: [],
        mensagem: "",
        assunto: "",
        categoria: "",
        anexo: "",
        telefone: "",
        loadingForm: false,
        read_only: false,
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0);
        Services.chamadosCategoria(this)
    }

    handleSubmit = e => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        this.setState({ loadingForm: true });
        e.preventDefault();

        let formData = new FormData();    //formdata object

        formData.append('mensagem', this.state.mensagem);   //append the values with key, value pair
        formData.append('assunto', this.state.assunto);
        formData.append('id_usuario', usuario.id);
        formData.append('token', token);
        formData.append('telefone', this.state.telefone);
        formData.append('categoria', this.state.categoria);
        formData.append('anexo', this.state.anexo);
        Services.cadastroChamados(this, formData);
    }

    handleInputChange = (event) => {
        if (event.target.name === 'anexo') {
            let arq = event.target.files[0];
            this.setState({ anexo: arq });
        }
        if (event.target.name === 'categoria') {
            this.setState({ categoria: event.target.value })
        }
        if (event.target.name === 'assunto') {
            this.setState({ assunto: event.target.value })
        }
        if (event.target.name === 'telefone') {
            this.setState({ telefone: event.target.value })
        }
        if (event.target.name === 'mensagem') {
            this.setState({ mensagem: event.target.value })
        }
    }

    responseChamadosCategoria(response) {
        if (response.success === 'true') {
            this.setState({ categorias: response.chamados_categorias })
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    handleCloseAlert = () => {
        if (this.state.alertStatus === "success") {
            this.setState({ redirect: 'chamados' })
            return
        }
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
    }

    responseCadastroChamados(response) {
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
            this.setState({ alertMessage: "Tudo certo! Teu chamado foi cadastrado com sucesso!" })
            this.setState({ alertStatus: 'success' })
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
                            <Link to='/chamados'>
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
                                <Link to="/novochamado">
                                    Novo chamado
                            </Link>
                            </Breadcrumbs>
                        </Container>

                        <Grid container>
                            <Grid item xs={12} lg={12}>
                                <Typography component="h2" variant="h5" align="center" className={classes.title} paragraph>
                                    Novo Chamado
                                </Typography>
                            </Grid>

                        </Grid>

                        <Container maxWidth="sm" className={classes.profile}>
                            <form className={classes.form} onSubmit={this.handleSubmit}>
                                <Grid container spacing={2}>
                                    <Grid item xs={12} sm={12}>
                                        <FormControl variant="outlined" className="w-100">
                                            <InputLabel id="categoria">Categoria</InputLabel>
                                            <Select
                                                label="Categoria"
                                                onChange={this.handleInputChange}
                                                value={this.state.categoria}
                                                inputProps={{
                                                    name: "categoria",
                                                    id: "categoria"
                                                }}
                                                fullWidth
                                            >
                                                <MenuItem value={""}>
                                                    Selecione uma categoria
                                                </MenuItem>
                                                {this.state.categorias.map((categorias) => {
                                                    return <MenuItem value={categorias.id}> {categorias.titulo}</MenuItem>
                                                })};
                                            </Select>
                                        </FormControl>
                                    </Grid>

                                    <Grid item xs={12} sm={12}>
                                        <TextField
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
                                            name="assunto"
                                            variant="outlined"
                                            fullWidth
                                            id="assunto"
                                            label="Assunto"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>

                                    <Grid item xs={12} sm={12}>
                                        <TextField
                                            name="mensagem"
                                            variant="outlined"
                                            required
                                            fullWidth
                                            multiline
                                            rows={4}
                                            id="mensagem"
                                            label="Mensagem"
                                            color="secondary"
                                            onChange={this.handleInputChange}
                                        />
                                    </Grid>

                                    <Grid item xs={12} lg={12}>
                                        <InputLabel id="categoria">Selecione um arquivo:</InputLabel>
                                        <label htmlFor="file" className='anexo'>
                                            <SaveIcon htmlFor="file" fontSize='large' color="secondary" />
                                        </label>
                                        <Input id="file" name="anexo" type="file" onChange={this.handleInputChange} style={{ display: 'none' }} />
                                        <If condition={this.state.anexo !== ""}>
                                            <Typography variant="body2" color="secondary" component="p">
                                                Um arquivo foi selecionado!
                                            </Typography>
                                        </If>
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
                                            Abrir chamado
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


NewCall.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(NewCall);