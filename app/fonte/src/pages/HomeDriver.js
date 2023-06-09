
import React from 'react';
import { Link, Redirect } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import {
    Container, Typography, Grid, Snackbar,
    Button, Slide, Breadcrumbs, Badge, LinearProgress, FormControl,
    Select, MenuItem, InputLabel
} from '@material-ui/core';
import { Dialog, DialogActions, DialogContent, DialogTitle, DialogContentText, Switch, FormGroup, FormControlLabel } from '@material-ui/core';
import { Card, CardActionArea, CardActions, CardContent, CardMedia } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import AppMenuDriver from '../components/MenuDriver';
import Services from "../Services";
import Footer from '../components/Footer';
import ReactTimeout from 'react-timeout';
import CachedIcon from '@material-ui/icons/Cached';
import Add from '@material-ui/icons/Add';
import AlertOrders from '../components/AlertOrders';
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

class HomeDriver extends React.Component {

    state = {

        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        openmodal: false,
        pedidos: [],
        redirect: '',
        timeout: '',
        border: 'none',
        numero_pedidos: 0,
        entregador_status: '0',
    }

    componentDidMount() {
        const geolocation = navigator.geolocation.getCurrentPosition(this.onSuccess);
        this.exibePedidos();
    }

    onSuccess = (position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        console.log(latitude);

        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.entregadoresGeolocalizacao(this, usuario.id, token, latitude, longitude);
        Services.dadosEntregador(this, usuario.id, token);
    };

    exibePedidos = () => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let filtro = JSON.parse(localStorage.getItem('filtro')) || '';

        Services.pedidosEntregador(this, usuario.id, token, filtro)
    }

    responseEntregadoresGeolocalizacao(response) {
        this.reload = this.props.setInterval(this.reload, 60000)
    }

    responseDadosEntregador(response) {

        if (response.status_disponibilidade === '1' || response.status_disponibilidade === 1) {
            this.setState({ entregador_status: true })
        }
        else {
            this.setState({ entregador_status: false })
        }
    }

    reload = () => {
        window.location.reload();
    }

    handleCloseAlert = () => {
        this.setState({ openAlert: false })
        this.setState({ alertMessage: '' })
        this.setState({ alertStatus: '' })
        window.location.reload();
    }

    responsePedidos(response) {
        if (response.error === 'true' && response.type !== 'token_invalido') {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
        }

        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
        }

        if (response.success === 'true') {
            this.setState({ pedidos: response.pedidos })

            if (response.pedidos.length > 0) {
                this.setState({ numero_pedidos: response.pedidos.length })
            }
        }
    }

    filtro = e => {
        if (e.target.value === '0') {
            localStorage.removeItem('filtro');
            window.location.reload();
            return
        }
        localStorage.setItem('filtro', e.target.value);
        this.exibePedidos();
    }

    finishOrder = (pedido) => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.entregarPedidoEntregador(this, usuario.id, token, pedido)
    }

    aceptOrder = (pedido) => {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.aceitarPedidoEntregador(this, usuario.id, token, pedido)
    }

    responsePedidoAceitar(response) {
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
            this.setState({ alertMessage: "Tudo certo! Pedido Aceito!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }

    responsePedidoEntregar(response) {
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
            this.setState({ alertMessage: "Tudo certo! Pedido entregue!" })
            this.setState({ alertStatus: 'success' })
            return
        }
    }
    handleChangeStatus = (e) =>{
        this.setState({ entregador_status: e.target.checked })
        
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        if (e.target.checked === true){
            Services.statusEntregador(this, usuario.id, token, '1')
            return
        }
        else{
            Services.statusEntregador(this, usuario.id, token, '0')
            return
        }

    }

    responseStatusEntregador(response){
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
            this.setState({ alertMessage: "Tudo certo! Status alterado!" })
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

                <AppMenuDriver />

                <main>
                    <div className={classes.topSpace} />

                    <Container maxWidth="md" className={classes.container}>
                    <FormGroup row>
                                    <FormControlLabel
                                        control={
                                            <Switch
                                                checked={this.state.entregador_status}
                                                onChange={this.handleChangeStatus}
                                                name="statusEntregador"
                                                color="primary"
                                                size="large"
                                            />
                                        }
                                        label="Estou pronto para realizar entregas!"
                                    />
                                </FormGroup>
                    </Container>

                    <Container maxWidth="md" className={classes.container}>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} align="center">
                                <Typography component="h1" variant="h6" align="center" className={classes.title}>
                                    Pedidos
                                    </Typography>
                            </Grid>
                            <Grid item container xs={12} lg={12} spacing={2}>
                                <Grid item xs={false} lg={3}></Grid>
                                <Grid item xs={12} lg={6}>
                                    <FormControl variant="outlined" className={classes.formControl}>
                                        <InputLabel id="ordenar">Filtrar</InputLabel>
                                        <Select
                                            label="Ordenar"
                                            onChange={this.filtro}
                                            value={localStorage.getItem('filtro') || ''}
                                            inputProps={{
                                                name: "ordenar",
                                                id: "ordenar"
                                            }}
                                        >
                                            <MenuItem value={'0'}>Todos</MenuItem>
                                            <MenuItem value={'1'}>Aguardando aprovação</MenuItem>
                                            <MenuItem value={'2'}>Aceito</MenuItem>
                                            <MenuItem value={'3'}>Processo de Entrega</MenuItem>
                                            <MenuItem value={'4'}>Pedido Entregue</MenuItem>
                                            <MenuItem value={'5'}>Cancelado</MenuItem>
                                        </Select>
                                    </FormControl>
                                </Grid>
                                <Grid item xs={false} lg={3}></Grid>
                                <Grid item xs={false} lg={3}></Grid>
                                <Grid item xs={12} lg={6}>
                                    <Button
                                        color="secondary"
                                        variant="contained"
                                        fullWidth
                                        startIcon={<CachedIcon />}
                                        onClick={() => window.location.reload()}
                                    >Recarregar a página</Button></Grid>
                                <Grid item xs={false} lg={3}></Grid>
                            </Grid>

                            <Grid item xs={12} lg={12}>
                                <AlertOrders />
                            </Grid>

                            <If condition={this.state.pedidos.length === 0}>
                                <Grid item xs={12} lg={12} id="produtos" align="center">
                                    Não há pedidos no momento.
                                </Grid>
                            </If>

                            {this.state.pedidos.map((pedidos) => {
                                return <Grid item xs={12} md={12} key={pedidos.id}>
                                    <If condition={pedidos.status === 1 || pedidos.status === '1'}>
                                        <Card raised={true} className={classes.root} style={{ border: '2px solid #d32f2f', marginBottom: 8 }}>
                                            <Link to={"/pedidoentregador/" + pedidos.id}><CardActionArea>
                                                <CardContent>

                                                    <LinearProgress color="primary" variant="determinate" value={parseInt(pedidos.status) * 25} />

                                                    <Typography gutterBottom variant="h5" component="h2" color="secondary" paragraph>
                                                        Status do pedido: {pedidos.status_titulo}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Pedido nº: {pedidos.id}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Data do pedido: {pedidos.data_hora_registro}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Itens: {pedidos.produtos}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Endereço: {pedidos.endereco} , {pedidos.numero} - {pedidos.complemento} - {pedidos.bairro} - {pedidos.cidade}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Distância aproximada: {pedidos.distancia} KM
                                            </Typography>
                                                </CardContent>
                                            </CardActionArea></Link>
                                            <CardActions>
                                                <Link to={"/pedidoentregador/" + pedidos.id}><Button variant="contained" color="secondary">
                                                    Detalhes do pedido
                      </Button></Link>
                                                <Button
                                                    className="button-driver-acept"
                                                    variant="contained"
                                                    disabled={this.state.loadingForm}
                                                    onClick={() => this.aceptOrder(pedidos.id)}
                                                >Aceitar Pedido</Button>
                                            </CardActions>
                                        </Card></If>

                                    <If condition={pedidos.status === 3 || pedidos.status === '3'}>
                                        <Card raised={true} className={classes.root} style={{ border: '2px solid #388e3c', marginBottom: 8 }}>
                                            <Link to={"/pedidoentregador/" + pedidos.id}><CardActionArea>
                                                <CardContent>
                                                    <LinearProgress color="primary" variant="determinate" value={parseInt(pedidos.status) * 25} />

                                                    <Typography gutterBottom variant="h5" component="h2" color="secondary" paragraph>
                                                        Status do pedido: {pedidos.status_titulo}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Pedido nº: {pedidos.id}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Data do pedido: {pedidos.data_hora_registro}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Itens: {pedidos.produtos}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Endereço: {pedidos.endereco} , {pedidos.numero}- {pedidos.complemento} - {pedidos.bairro} - {pedidos.cidade}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Distância aproximada: {pedidos.distancia} KM
                                            </Typography>
                                                </CardContent>
                                            </CardActionArea></Link>
                                            <CardActions>
                                                <Link to={"/pedidoentregador/" + pedidos.id}><Button variant="contained" color="secondary">
                                                    Detalhes do pedido
                      </Button></Link>
                                                <Button
                                                    className="button-driver-send"
                                                    variant="contained"
                                                    disabled={this.state.loadingForm}
                                                    onClick={() => this.finishOrder(pedidos.id)}
                                                >Marcar como entregue</Button>
                                            </CardActions>
                                        </Card></If>

                                    <If condition={pedidos.status === 4 || pedidos.status === '4' ||
                                        pedidos.status === 5 || pedidos.status === '5'}>
                                        <Card raised={true} className={classes.root}>
                                            <Link to={"/pedidoentregador/" + pedidos.id}><CardActionArea>
                                                <CardContent>

                                                    <If condition={pedidos.status !== 5 && pedidos.status !== '5'}>
                                                        <LinearProgress color="primary" variant="determinate" value={parseInt(pedidos.status) * 25} />
                                                    </If>

                                                    <If condition={pedidos.status === 5 || pedidos.status === '5'}>
                                                        <Badge overlap="rectangular" color="error" variant="dot">
                                                        </Badge>
                                                    </If>
                                                    <Typography gutterBottom variant="h5" component="h2" color="secondary" paragraph>
                                                        Status do pedido: {pedidos.status_titulo}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Pedido nº: {pedidos.id}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Data do pedido: {pedidos.data_hora_registro}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Itens: {pedidos.produtos}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Endereço: {pedidos.endereco} , {pedidos.numero}- {pedidos.complemento} - {pedidos.bairro} - {pedidos.cidade}
                                                    </Typography>
                                                    <Typography variant="body2" color="secondary" component="p">
                                                        Distância aproximada: {pedidos.distancia} KM
                                            </Typography>
                                                </CardContent>
                                            </CardActionArea></Link>
                                            <CardActions>
                                                <Link to={"/pedidoentregador/" + pedidos.id}><Button variant="contained" color="secondary">
                                                    Detalhes do pedido
                                                </Button></Link>
                                            </CardActions>
                                        </Card></If>

                                </Grid>
                            })}

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
        </div >
    }
}

HomeDriver.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default ReactTimeout(withStyles(useStyles)(HomeDriver));