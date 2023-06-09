import { ContentCopy, PersonSearch, WorkOff, Link as LinkIcon, ArrowBackRounded } from "@mui/icons-material";
import { Box, Button, Card, CardMedia, Grid, IconButton, SvgIcon, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography, useTheme } from "@mui/material";
import { useContext, useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import AuthContext from "../contexts/AuthProvider";
import mock_icon from '../data/mock_icon.jpg';
import api from "../services/api";

export default function JobApplications() {

    const [data, setData] = useState([]);

    const { id } = useParams();

    useEffect(() => {
        api.post('lista-candidatos-vaga.php', {
            id_vaga: id
        })
            .then(res => {
                if (res.data.success) {
                    setData(res.data.candidatos);
                } else {
                    alert('Ocorreu um erro ao carregar sua página home!');
                };
            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    }, []);

    const styles = {
        centralizer: {
            maxWidth: '1280px',
        },
        search: {
            mb: '1em'
        },
        searchContent: {
            p: '1em',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'start',
            flexDirection: 'column'
        },
        jobItem: {
            m: '1em 0 0 0',
        },
        noJob: {
            p: '2em',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column'
        },
        jobButton: {
            p: '1em',
            display: 'flex',
            justifyContent: 'start',
            alignItems: 'center',
            flexDirection: 'row',
            width: '100%',
            height: '100%'
        },
        cardMedia: {
            m: '1em 1em 1em 0',
            borderRadius: '20%',
            width: '8em',
            height: '8em',
        },
        boxDesc: {
            display: 'flex',
            justifyContent: 'start',
            alignItems: 'center',
            flexDirection: 'row',
            mt: '.5em'
        },
        icon: {
            mr: '.35em',
            color: 'primary'
        },
    };

    const theme = useTheme();

    const columns = [
        {
            id: 1,
            label: 'Icone',
            minWidth: 'auto',
            align: 'left',
            // format: (value: number) => value.toLocaleString('en-US'),
        },
        {
            id: 2,
            label: 'Nome',
            minWidth: 170,
            align: 'left',
            // format: (value: number) => value.toLocaleString('en-US'),
        },
        {
            id: 3,
            label: 'Profissão',
            minWidth: 'auto',
            align: 'left',
            // format: (value: number) => value.toLocaleString('en-US'),
        },
        {
            id: 4,
            label: 'Dada de candidatura',
            minWidth: 'auto',
            align: 'left',
            // format: (value: number) => value.toLocaleString('en-US'),
        },
        {
            id: 5,
            label: 'Portfólio',
            minWidth: 'auto',
            align: 'left',
            // format: (value: number) => value.toLocaleString('en-US'),
        },
        {
            id: 6,
            label: 'Visitar',
            minWidth: 'auto',
            align: 'left',
            // format: (value: number) => value.toLocaleString('en-US'),
        },
    ];

    return (
        <Grid container sx={styles.centralizer}>
            <Grid item xs={12} m={'1em 0'}>
                <Button component={Link} to={"/"} startIcon={<ArrowBackRounded />}>
                    Voltar para vagas
                </Button>
            </Grid>
            <Grid item xs={12}>
                <Typography variant="h1" color={'white'}>
                    Candidaturas
                </Typography>
            </Grid>
            <Grid item xs={12} p='1em'>
                <Card component={Grid} container>
                    {
                        data ?
                            <TableContainer sx={{ maxHeight: '80vh' }}>
                                <Table stickyHeader aria-label="sticky table">
                                    <TableHead>
                                        <TableRow>
                                            {columns.map((column) => (
                                                <TableCell
                                                    align={column.align}
                                                >
                                                    <Typography fontWeight={700} color="primary">
                                                        {column.label}
                                                    </Typography>
                                                </TableCell>
                                            ))}
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {data.map((row) => {
                                            return (
                                                <TableRow hover key={row.id}>
                                                    <TableCell align={row.align}>
                                                        <CardMedia sx={{ borderRadius: '50%', width: '4em', border: '2px solid black' }} component='img' image={row.img} alt={`Imagem de ${row.nome}`} />
                                                    </TableCell>
                                                    <TableCell align={row.align}>
                                                        <Typography fontWeight={700}>
                                                            {row.nome}
                                                        </Typography>
                                                    </TableCell>
                                                    <TableCell align={row.align}>
                                                        <Typography>
                                                            {row.profissao}
                                                        </Typography>
                                                    </TableCell>
                                                    <TableCell align={row.align}>
                                                        <Typography>
                                                            {row.data_candidatura}
                                                        </Typography>
                                                    </TableCell>
                                                    <TableCell align={row.align}>
                                                        <IconButton disabled={!row.portfolio} href={row.portfolio} target="_blank">
                                                            <LinkIcon color={row.portfolio ? 'primary' : 'gray'} fontSize="large" />
                                                        </IconButton>
                                                    </TableCell>
                                                    <TableCell align={row.align}>
                                                        <IconButton component={Link} to={`/VisualizarCandidato/${row.id}`}>
                                                            <PersonSearch color="primary" fontSize="large" />
                                                        </IconButton>
                                                    </TableCell>
                                                </TableRow>
                                            );
                                        })}
                                    </TableBody>
                                </Table>
                            </TableContainer>
                            :
                            <Grid item xs={12} sx={styles.noJob}>
                                <WorkOff fontSize="large" sx={{ color: 'red' }} />
                                <Typography variant="h2" mt={'16px'} textAlign="center">
                                    Ops! Ainda não existem candidaturas para essa vaga.
                                </Typography>
                                <Button sx={{ mt: '16px' }} startIcon={<ContentCopy />} mt={'16px'} variant="contained" onClick={() => { navigator.clipboard.writeText('URL da vaga em questão') }}>
                                    Compartilhar esta vaga agora!
                                </Button>
                            </Grid>
                    }
                </Card>
            </Grid>
        </Grid>
    )
}