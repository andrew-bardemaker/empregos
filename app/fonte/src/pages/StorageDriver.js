
import React from 'react';
import { Link } from 'react-router-dom'
import { withStyles } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';
import { Container, Typography, Grid, Snackbar, TextField, Badge, Button } from '@material-ui/core';
import MuiAlert from '@material-ui/lab/Alert';
import PropTypes from 'prop-types';
import Services from "../Services";
import Footer from '../components/Footer';
import ArrowBackIcon from '@material-ui/icons/ArrowBack';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableContainer from '@material-ui/core/TableContainer';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import Paper from '@material-ui/core/Paper';
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    BrowserView,
} from "react-device-detect";
import { If } from 'react-if';
import AppMenuDriver from '../components/MenuDriver';

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

class StorageDriver extends React.Component {

    state = {

        openAlert: false,
        alertMessage: '',
        alertStatus: 'info',
        estoque: [],
        logged: false,
        busca: '',
        columns: [
            { field: 'id', headerName: 'Id', width: 70 },
            { field: 'titulo', headerName: 'Titulo', width: 130 },
            { field: 'qntd', headerName: 'Quantidade', width: 70 },
        ],
        rows: [
            { id: '1', titulo: 'teste', qntd: '2' },
            { id: '1', titulo: 'teste', qntd: '2' },
            { id: '1', titulo: 'teste', qntd: '2' },
            { id: '1', titulo: 'teste', qntd: '2' },
        ],
    }

    componentDidMount() {
        document.getElementById("top").scroll(0, 0);
        var usuario = JSON.parse(localStorage.getItem('user')) || '';
        var token = JSON.parse(localStorage.getItem('token')) || '';

        Services.estoque(this, usuario.id, token);
    }

    responseEstoque(response) {
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
            this.setState({ estoque: response.produtos })
        }
    }

    render() {
        const { classes } = this.props;
        return <div className={classes.root} id="top">
            <ThemeProvider theme={theme}>
                <AppMenuDriver />

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
                                    Meu Estoque
                                    </Typography>
                            </Grid>

                            <If condition={this.state.estoque !== []}>
                            <Grid item xs={12} sm={12}>
                                <TableContainer component={Paper}>
                                    <Table className={classes.table} size="small" aria-label="a dense table">
                                        <TableHead>
                                            <TableRow>
                                                <TableCell className={classes.small_title}>Produto</TableCell>
                                                <TableCell className={classes.small_title} align="right">Quantidade</TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>
                                            {this.state.estoque.map((estoque) => {
                                                return <TableRow key={estoque.titulo}>
                                                    <TableCell component="th" scope="row">
                                                        {estoque.titulo}
                                                    </TableCell>
                                                    <TableCell align="right">{estoque.qntd}</TableCell>
                                                </TableRow>
                                            })}
                                        </TableBody>
                                    </Table>
                                </TableContainer>
                            </Grid>
                            </If>

                            <If condition={this.state.estoque === []}>
                                <Grid item xs={12} sm={12} align="center" justifyContent="center">
                                    <Typography variant="body2" color="secondary" gutterBottom>
                                        Não há informações sobre o estoque. Aguarde.
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

StorageDriver.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(StorageDriver);