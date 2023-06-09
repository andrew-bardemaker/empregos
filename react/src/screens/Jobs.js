import { AttachMoneyRounded, LocationOnRounded, MedicalServicesRounded, WorkOff } from "@mui/icons-material";
import { Box, Button, Card, CardMedia, Grid, Typography } from "@mui/material";
import { useContext, useEffect, useState } from "react";
import { Link } from "react-router-dom";
import AuthContext from "../contexts/AuthProvider";
import api from "../services/api";

export default function Jobs() {

    const [data, setData] = useState([]);

    const { userData } = useContext(AuthContext);

    useEffect(() => {
        api.post('vagas-empresa.php', {
            id_empresa: userData.id
        })
            .then(res => {
                if (res.data.success) {
                    setData(res.data.vagas);
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
            m: '1em 0'
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
            m: '1em 0 0 0'
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
            height: '100%',
            bgolor: 'grey'
        },
        cardMedia: {
            m: '1em 1em 1em 0',
            borderRadius: '20%',
            width: '8em',
            height: '8em'
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

    return (
        <Grid container spacing={1} sx={styles.centralizer}>
            <Grid item xs={12}>
                <Typography variant="h1" color={'white'}>
                    Minhas vagas
                </Typography>
            </Grid>
            <Grid item xs={12}>
                <Card component={Grid} container p={'1em'}>
                    {
                        data ?
                            data.map((job, index) => {
                                return (
                                    <Grid item key={index} component={Card} xs={12} sx={styles.jobItem}>
                                        <Button component={Link} variant={job.status === '1' ? 'outlined' : 'text'} to={`/MinhasVagasDetalhes/${job.id_vaga}`} sx={styles.jobButton}>
                                            <CardMedia
                                                component={'img'}
                                                image={`https://dedstudio.com.br/bejobs/images/vagas/${job.imagem}`}
                                                alt={job.titulo}
                                                sx={styles.cardMedia}
                                            />
                                            <Box>
                                                <Typography>
                                                    {job.profissao}
                                                </Typography>
                                                <Typography variant="h2" color="primary">
                                                    {job.titulo}
                                                </Typography>
                                                <Box sx={styles.boxDesc}>
                                                    <AttachMoneyRounded color="primary" sx={styles.icon} />
                                                    <Typography>
                                                        {job.pagamento}
                                                    </Typography>
                                                </Box>
                                                <Box sx={styles.boxDesc} >
                                                    <LocationOnRounded color="primary" sx={styles.icon} />
                                                    <Typography>
                                                        {job.bairro + ', ' + job.cidade + ', ' + job.estado}
                                                    </Typography>
                                                </Box>
                                                <Box sx={styles.boxDesc}>
                                                    <MedicalServicesRounded color="primary" sx={styles.icon} />
                                                    <Typography>
                                                        {job.nome_empresa}
                                                    </Typography>
                                                </Box>
                                            </Box>
                                        </Button>
                                    </Grid>
                                )
                            })
                            :
                            <Grid item component={Card} xs={12} sx={styles.noJob}>
                                <WorkOff fontSize="large" sx={{ color: 'red' }} />
                                <Typography variant="h2" mt={'16px'}>
                                    Ops! Você ainda não cadastrou nenhuma vaga
                                </Typography>
                                <Button sx={{ mt: '16px' }} component={Link} to="/CadastrarVagas" mt={'16px'} variant="contained">
                                    Cadastrar vagas agora!
                                </Button>
                            </Grid>
                    }
                </Card>
            </Grid>
        </Grid>
    )
}