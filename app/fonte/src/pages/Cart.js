
import React, { useEffect } from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import {
    CssBaseline, Container, Typography, Button,
    Grid, Breadcrumbs, ButtonGroup, TextField, Snackbar, LinearProgress
} from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle, } from '@material-ui/core';
import PropTypes from 'prop-types';
import AppMenu from '../components/Menu';
import AppMenuLogged from '../components/MenuUser';
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import Add from '@material-ui/icons/Add';
import Services from "../Services";
import Remove from '@material-ui/icons/Remove';
import AlertOpen from '../components/AlertOpen';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import MuiAlert from '@material-ui/lab/Alert';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import {
    BrowserView,
    MobileView,
} from "react-device-detect";
import { If } from 'react-if';


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

class Cart extends React.Component {

    state = {
        quantidade: 4,
        action: 'list',
        listCarrinho: [],
        totalCarrinho: 0,
        logged: false,
        openAlert: false,
        openmodal: false,
        pedidoMinimo: '',
        pedidoReal: '',
        disabled: false,
        redirect: '',
        funcionamento:false,
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0)
        let carrinho = localStorage.getItem('carrinho') || '';
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        if (usuario !== '' && token !== '') {
            this.setState({ logged: true });
        }

        if (carrinho !== '') {
            const cart = [{
                id_carrinho: carrinho,
                id_produto: "",
                qntd: "",
                action: "list_produtos"
            }];
            Services.carrinho(this, usuario.id, token, cart[0]);
        }

        Services.pedidoMinimo(this);
        Services.horarioFuncionamento2(this);
    }

    responseHorarioFuncionamento2(response){
        if (response.success === 'true') {
            if(response.status_aberto === '1' || response.status_aberto === 1){
            this.setState({ funcionamento: true})
            }
            else{
                this.setState({ funcionamento: false})
            }
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    responsePedidoMinimo(response){
        if(response.success){
        this.setState({pedidoMinimo: response.valor})
        this.setState({pedidoReal: response.valor_moeda})}
    }

    onChangeQuantidade = (e) => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';
        let quant = e.target.value;
        let id_produto = e.target.name;

        if (e.target.value === '') {
            return
        }

        this.setState({ action: 'up' })

        const cart = [{
            id_carrinho: carrinho,
            id_produto: id_produto,
            qntd: parseInt(quant),
            action: "up_produto"
        }];

        Services.carrinho(this, usuario.id, token, cart[0]);
    }

    plus1 = (produto) => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        this.setState({ action: 'add_plus' })

        const cart = [{
            id_carrinho: carrinho,
            id_produto: produto,
            qntd: 1,
            action: "add_produto"
        }];

        Services.carrinho(this, usuario.id, token, cart[0]);
    }

    removeAdd = (produto, qnt) => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';
        let id_produto = produto;
        let quant_prod = qnt;

        this.setState({ action: 'up' })

        const cart = [{
            id_carrinho: carrinho,
            id_produto: id_produto,
            qntd: parseInt(quant_prod) - 1,
            action: "up_produto"
        }];

        Services.carrinho(this, usuario.id, token, cart[0]);
    }

    remove = (produto) => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let carrinho = localStorage.getItem('carrinho') || '';
        let id_produto = produto;

        this.setState({ action: 'del' })

        const cart = [{
            id_carrinho: carrinho,
            id_produto: id_produto,
            qntd: "",
            action: "del_produto"
        }];

        Services.carrinho(this, usuario.id, token, cart[0]);
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    responseCarrinho(response) {
        if (response.success === 'true') {
            if (this.state.action === 'criar') {
                localStorage.removeItem('carrinho');
                localStorage.setItem('carrinho', response.id_carrinho);
                return
            }
            if (this.state.action === 'list') {
                this.setState({ listCarrinho: response.produtos })
                this.setState({ totalCarrinho: response.total_carrinho })

                if(response.produtos.length >0){
                    localStorage.removeItem('carrinhoList');
                    localStorage.setItem('carrinhoList', 'ok');
                    return
                }
                return
            }
            if (this.state.action === 'add') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "O produto foi adicionado ao pedido!" })
                this.setState({ alertStatus: 'success' })
                return
            }
            if (this.state.action === 'up') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "Quantidade atualizada!" })
                this.setState({ alertStatus: 'success' })
                return
            }

            if (this.state.action === 'add_plus') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "Quantidade atualizada!" })
                this.setState({ alertStatus: 'success' })
                return
            }

            if (this.state.action === 'del') {
                this.setState({ openAlert: true })
                this.setState({ alertMessage: "Produto removido com sucesso!" })
                this.setState({ alertStatus: 'success' })
                return
            }
        }
        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }
        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    handleClose = () => {
        this.setState({ openmodal: false })
    }

    goToPay = () =>{
        if (parseFloat(this.state.totalCarrinho)< parseFloat(this.state.pedidoMinimo)){
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa! Tu ainda não atingiu o valor mínimo de compra!" })
            this.setState({ alertStatus: 'error' })
            return
        }
        else{
            this.setState({redirect: 'checkout'})
        }
    }

    render() {
        const { classes } = this.props;
        const calcCarrinho =(parseFloat(this.state.pedidoMinimo) - parseFloat(this.state.totalCarrinho));
        if (this.state.redirect) {
            return (<Redirect to={'/' + this.state.redirect} />);
        }
        return (<div className={classes.rootLightGray} id="top">
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
                        <Link to={'./produtos'}><Button color="secondary" startIcon={<ArrowBackIcon />} variant='contained'>
                            Voltar
                        </Button></Link>
                    </Container>

                    <Container maxWidth="lg" className={classes.container}>
                        <Grid container spacing={2}>
                            <Grid item xs={12} lg={12}>
                                <Typography component="h1" variant="h5" align="center" className={classes.title} paragraph>
                                    Meus envios
                            </Typography>
                            </Grid>

                            <If condition={calcCarrinho > 0}>
                            <Grid item xs={12} lg={12}>
                                <Typography component="h2" variant="h6" color="secondary" paragraph align="center">
                                    Faltam R${calcCarrinho.toFixed(2)} para atingir o valor mínimo de compra.
                            </Typography>
                            </Grid>
                            </If>
                            <Grid item xs={12} lg={3}></Grid>
                            <If condition={calcCarrinho > 0 && this.state.totalCarrinho >0}>
                            <Grid item xs={12} lg={6}>
                                <LinearProgress color="primary" variant="determinate" value={parseInt((parseInt(this.state.totalCarrinho)*100) / parseInt(this.state.pedidoMinimo))} />
                            </Grid>
                            </If>
                            
                        </Grid>
                    </Container>

                    <Container maxWidth="lg" className={classes.containerRounded}>
                        {this.state.listCarrinho.map((produtos) => {
                            return <Grid className={classes.gridProducts} container spacing={2} align="center" direction="row" key={produtos.id}
                                justifyContent="center"
                                alignContent='center'
                                alignItems="center">
                                <Grid item xs={3} lg={2}>
                                    <img src={produtos.foto_pequena} className="w-100"/>
                                </Grid>
                                <Grid item xs={7} lg={9} >
                                    <Typography gutterBottom color="secondary" variant="h6" component="h2" paragraph>
                                        {produtos.titulo}
                                    </Typography>
                                    <input type="hidden" name="id_produto" value="produtos.id" />
                                    <ButtonGroup color="secondary" aria-label="contained secondary button group">
                                        <Button
                                            onClick={() => this.removeAdd(produtos.id, produtos.qntd)}
                                        >{<Remove />}</Button>
                                        <Button><TextField
                                            className="textFieldProducts"
                                            placeholder={produtos.qntd}
                                            name={produtos.id}
                                            id={produtos.qntd}
                                            onBlur={this.onChangeQuantidade} />
                                        </Button>
                                        <Button onClick={() => this.plus1(produtos.id)}>{<Add />}</Button>
                                    </ButtonGroup>
                                </Grid>

                                <Grid item xs={2} lg={1}>
                                    <Button onClick={() => this.remove(produtos.id)}><FontAwesomeIcon className="iconTrash" size="lg" color="secondary" icon={faTrash} /></Button>
                                </Grid>
                            </Grid>
                        })}
                        <If condition={this.state.listCarrinho.length === 0}>{
                            <Grid item xs={12} lg={12}>
                                <Typography component="h3" variant="h6" color="secondary" paragraph align="center">
                                    Ainda não há itens no seu pedido.
                            </Typography>
                            </Grid>
                        }
                        </If>
                    </Container>


                    <Container maxWidth="lg" className={classes.container}>

                        <Grid container spacing={2} direction="row" align="center" justifyContent="center" alignItems="center">

                            <Grid item xs={12} lg={6}></Grid>
                            
                            <Grid item xs={12} lg={6}>
                                <Typography variant="h6" color="secondary" paragraph>
                                    Valor total: R$ {this.state.totalCarrinho}
                                </Typography>

                                <Typography variant="h6" color="primary" paragraph>
                                    Entrega grátis
                                </Typography>

                                <Typography variant="body2" color="secondary" paragraph>
                                    Possui um cupom de desconto? Insira na etapa de pagamento.
                                </Typography>

                            </Grid>

                            <Grid item xs={12} lg={6}>
                                <Button color="secondary" variant='contained' className="w-100">
                                    <Link to='/produtos'> Continuar Comprando </Link>
                                </Button>
                            </Grid>

                            <Grid item xs={12} lg={6}>
                                <If condition={this.state.logged === false && this.state.listCarrinho.length > 0 && this.state.funcionamento === true}>
                                    <Button color="primary" variant='contained' className="w-100" onClick={() => this.setState({ openmodal: true })}>
                                        Fazer login para continuar
                                    </Button>
                                </If>
                                <If condition={this.state.logged === true && this.state.listCarrinho.length > 0 && this.state.funcionamento === true} >
                                    <Button color="primary" variant='contained' className="w-100" onClick={this.goToPay}>
                                         Ir para pagamento
                                    </Button>
                                </If>
                            </Grid>

                        </Grid>

                    </Container>

                    <Container maxWidth="lg">
                        <Snackbar open={this.state.openAlert} autoHideDuration={5000} onClose={this.handleCloseAlert}>
                            <Alert severity={this.state.alertStatus} onClose={this.handleCloseAlert}>
                                {this.state.alertMessage}
                            </Alert>
                        </Snackbar>
                    </Container>

                </main>

                <Dialog
                    open={this.state.openmodal}
                    keepMounted
                    aria-labelledby="alert-dialog-title"
                    aria-describedby="alert-dialog-description"
                    onClose={this.handleClose}
                >
                    <DialogTitle id="alert-dialog-title">
                        <Typography variant="h5" align="center" className={classes.title} paragraph>
                            Bah tchê! Tu precisas estar identificado para continuar:
                    </Typography>
                    </DialogTitle>
                    <DialogContent>
                        <Grid container spacing={2}>
                            <Grid item xs={12} lg={12}>
                                <Button color="primary" variant='contained' fullWidth>
                                    <Link to='/login'> Sou cadastrado e quero fazer login </Link>
                                </Button>
                            </Grid>
                            <Grid item xs={12} lg={12}>
                                <Button color="primary" variant='contained' fullWidth>
                                    <Link to='/cadastro'> Ainda não sou cadastrado </Link>
                                </Button>
                            </Grid>
                        </Grid>
                    </DialogContent>
                </Dialog>

                <BrowserView>
                    <Footer />
                </BrowserView>
            </ThemeProvider>
        </div>
        )
    }
}


Cart.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Cart);