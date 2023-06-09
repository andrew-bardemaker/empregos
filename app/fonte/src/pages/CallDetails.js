import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Card, CardContent } from '@material-ui/core';
import { Dialog, DialogContent, DialogTitle } from '@material-ui/core';
import { Container, Typography, Grid, Snackbar, Button, Breadcrumbs, 
    TextField, InputLabel, Input, Radio, Slide,
    RadioGroup, FormControlLabel, FormControl } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import AppMenuLogged from '../components/MenuUser';
import Services from "../Services";
import Footer from '../components/Footer';
import SaveIcon from '@material-ui/icons/AddPhotoAlternate';
import Download from '@material-ui/icons/GetApp';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import { If } from 'react-if';
import {
    BrowserView,
} from "react-device-detect";

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


function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}

class CallDetails extends Component {
    state = {
        chamado: [],
        interacoes: [],
        menu: false,
        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        anexo: "",
        openmodal: false,
        openAvaliacao:false,
        loadingForm:false,
        redirect:'',
    }

    componentDidMount() {
        var params = this.props.match.params;

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.chamadoInterna(this, usuario.id, token, params.id)
    }
    responseChamadoInterna(response) {
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
            this.setState({ chamado: response.chamado[0] })
            if (this.state.chamado.status_id === 4 || this.state.chamado.status_id === '4') {
                this.setState({ openmodal: true })
            }
            this.setState({ interacoes: response.chamado[1].interacoes })
        }
    }

    handleSubmit = e => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        this.setState({ loadingForm: true });
        e.preventDefault();

        let formData = new FormData();    //formdata object

        formData.append('mensagem', this.state.mensagem);   //append the values with key, value pair
        formData.append('id_usuario', usuario.id);
        formData.append('token', token);
        formData.append('anexo', this.state.anexo);
        formData.append('id_chamado', this.state.chamado.id);
        Services.chamadosInteracao(this, formData);
    }

    handleInputChange = (event) => {
        if (event.target.name === 'anexo') {
            let arq = event.target.files[0];
            this.setState({ anexo: arq });
        }
        if (event.target.name === 'mensagem') {
            this.setState({ mensagem: event.target.value })
        }
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    responseChamadosInteracao(response) {
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
            this.setState({ alertMessage: "Tudo certo! Tua mensagem foi enviada com sucesso!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    avaliar= (e) =>{

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        e.preventDefault();

        let chamado = this.state.chamado.id

        Services.chamadosAvaliacao(this, usuario.id, token, chamado, e.target.value);
        
    }

    responseChamadosAvaliacao(response) {
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
            this.setState({ alertMessage: "Tudo certo! Teu chamado foi finalizado com sucesso!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    handleClose = () =>{
        this.setState({openmodal: false});
    }

    handleCloseAvaliacao = () =>{
        this.setState({openAvaliacao: false});
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
                            <Link to={"/chamado/" + this.state.chamado.id}>
                                {this.state.chamado.assunto}
                            </Link>
                        </Breadcrumbs>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid container>
                            <Grid item xs={12} lg={12}>
                                <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>
                                    chamado : {this.state.chamado.assunto} #{this.state.chamado.id}
                                </Typography>
                                <Typography component="h2" variant="body2" align="center" color="secondary" paragraph>
                                    Status : {this.state.chamado.status}
                                </Typography>
                            </Grid>
                        </Grid>
                        <Grid container spacing={2}>
                            <Grid item xs={12} lg={12}>
                                <Card raised={true} className={classes.root} >
                                    <CardContent>
                                        <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                            {this.state.chamado.nome_usuario}
                                        </Typography>
                                        <Typography variant="body2" color="secondary" component="p">
                                            {this.state.chamado.mensagem}
                                        </Typography>
                                        <Typography variant="caption" color="secondary" component="p">
                                            {this.state.chamado.data_hora_registro}
                                        </Typography>
                                        <If condition={this.state.chamado.anexo !== ""}>
                                            <a href={this.state.chamado.anexo} target="_blank"  rel='noopener noreferrer' download>
                                                <Download fontSize='large' color="secondary" />
                                            </a>
                                        </If>
                                    </CardContent>
                                </Card>
                            </Grid>

                            {this.state.interacoes.map((interacao) => {
                                return <Grid item xs={12} lg={12}>
                                    <Card raised={true} className={classes.root} >
                                        <CardContent>
                                            <Typography gutterBottom className="productTitle" variant="h5" component="h2">
                                                {interacao.nome_usuario}
                                            </Typography>
                                            <Typography variant="body2" color="secondary" component="p">
                                                {interacao.mensagem}
                                            </Typography>
                                            <Typography variant="caption" color="secondary" component="p">
                                                {interacao.data_hora_registro}
                                            </Typography>
                                            <If condition={interacao.anexo}>
                                                <a href={interacao.anexo} target="_blank" rel='noopener noreferrer' download>
                                                    <Download fontSize='large' color="secondary" />
                                                </a>
                                            </If>
                                        </CardContent>
                                    </Card>
                                </Grid>
                            })}
                        </Grid>
                    </Container>

                    <If condition={this.state.chamado.status_id !== 5 && this.state.chamado.status_id !== '5'}>   
                    <Container maxWidth="md" className={classes.profile}>
                        <Grid container>
                            <Grid item xs={12} lg={12}>
                                <Typography component="h2" variant="h5" align="center" className={classes.title} paragraph>
                                    Nova Mensagem
                                </Typography>
                            </Grid>
                        </Grid>
                        <form className={classes.form} onSubmit={this.handleSubmit}>
                            <Grid container spacing={2}>

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
                                        color="primary"
                                        disabled={this.state.loadingForm}
                                        className={classes.submit}
                                        onSubmit={this.handleSubmit}
                                    >
                                        Enviar mensagem
                                    </Button>
                                </Grid>
                            </Grid>
                        </form>
                    </Container>
                    </If>

                    <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                        <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                            {this.state.alertMessage}
                        </Alert>
                    </Snackbar>

                    <Dialog
                        open={this.state.openmodal}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                        onClose={this.handleClose}
                    >
                        <DialogTitle id="alert-dialog-title">
                            <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>
                                Tchê! Teu atendimento foi finalizado pela nossa equipe.
                    </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <Grid container spacing={2}>
                                <Grid item xs={12} lg={12}>
                                    <Button color="secondary" variant='contained' onClick={this.handleClose} fullWidth>
                                        Meu problema não foi resolvido e quero enviar uma mensagem
                                    </Button>
                                </Grid>
                                <Grid item xs={12} lg={12}>
                                    <Button color="primary" variant='contained' onClick={() => {this.setState({openAvaliacao: true})}} fullWidth>
                                        Meu problema foi resolvido e quero avaliar o chamado
                                    </Button>
                                </Grid>
                            </Grid>
                        </DialogContent>
                    </Dialog>

                    <Dialog
                        open={this.state.openAvaliacao}
                        TransitionComponent={Transition}
                        keepMounted
                        aria-labelledby="alert-dialog-title"
                        aria-describedby="alert-dialog-description"
                        onClose={this.handleCloseAvaliacao}
                    >
                        <DialogTitle id="alert-dialog-title">
                            <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>
                                Tchê! Conta pra gente como foi o teu atendimento:
                    </Typography>
                        </DialogTitle>
                        <DialogContent>
                            <FormControl component="fieldset">
                                <RadioGroup aria-label="avaliacao" name="avaliacao" value={this.state.id_endereco} onChange={this.avaliar}>
                                     <FormControlLabel value='1' control={<Radio />} label="Péssimo" />
                                     <FormControlLabel value='2' control={<Radio />} label="Ruim" />
                                     <FormControlLabel value='3' control={<Radio />} label="Regular" />
                                     <FormControlLabel value='4' control={<Radio />} label="Bom" />
                                     <FormControlLabel value='5' control={<Radio />} label="Ótimo" />
                                     <FormControlLabel value='6' control={<Radio />} label="Não desejo avaliar" />
                                </RadioGroup>
                            </FormControl>
                        </DialogContent>
                    </Dialog>

                </main>

                <BrowserView>
                    <Footer />
                </BrowserView>
            </ThemeProvider>
        </div >
    }
}

CallDetails.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(CallDetails);