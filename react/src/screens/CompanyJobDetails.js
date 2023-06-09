import { ArrowBackRounded, AttachMoneyRounded, CorporateFareRounded, Delete, Edit, HowToReg, LocationOnRounded } from "@mui/icons-material";
import { Button, Card, CardMedia, Grid, Typography } from "@mui/material";
import { Box } from "@mui/system";
import { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import api from "../services/api";

export default function CompanyJobDetails() {

    const { id } = useParams();

    const navigate = useNavigate();

    const [data, setData] = useState({});

    function refreshData() {
        api.post('vagas-detalhes.php', { id })
            .then(res => {
                if (res.data.success) {
                    setData(res.data.vaga[0]);
                } else {
                    alert('Ocorreu um erro ao carregar sua página home!');
                };
            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    };

    function deleteJob() {
        api.post('vagas-excluir.php', { id_vaga: id })
            .then(res => {
                if (res.data.success) {
                    alert('Vaga excluída com sucesso!');
                    navigate('/');
                } else {
                    alert('Houve um erro ao tentar excluir sua vaga!');
                };
            })
            .catch(err => {
                alert('Houve um erro ao tentar excluir sua vaga!');
                console.log(err);
            });
    }

    useEffect(() => {
        refreshData();
    }, []);

    const styles = {
        centralizer: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            m: '1em'
        },
        grid: {
            maxWidth: '1280px',
            m: '1em',
            p: '1em'
        },
        descriptionHead: {
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center'
        },
        contentDescription: {
            minHeight: '300px'
        },
        descriptionHeadBox: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center'
        },
        cardMedia: {
            borderRadius: '20%',
            width: {
                xs: '70%',
            },
            height: {
                xs: '100%',
            },
            m: {
                xs: ' auto',
                sm: 0
            }
        },
        description: {
            m: '1em 0',
            display: 'flex',
            justifyContent: 'start',
            alignItems: 'center'
        },
        icons: {
            m: '0 .25em'
        },
        descriptionText: {
            fontWeight: 700,
        },
        buttonBox: {
            display: 'flex',
            justifyContent: {
                xs: 'center',
                sm: 'right'
            },
            alignItems: 'start',
            flexDirection: {
                xs: 'column',
                sm: 'row'
            }
        },
        actionButton: {
            width: {
                xs: '100%',
                sm: 'auto'
            },
            mt: {
                xs: '1em',
                sm: '0'
            },
            ml: {
                xs: '0',
                sm: '1em'
            },
        }
    };

    return (
        <Grid component={Card} container sx={styles.grid}>
            <Grid item xs={12} m={'1em 0'}>
                <Button component={Link} to={"/"} startIcon={<ArrowBackRounded />}>
                    Voltar para vagas
                </Button>
            </Grid>
            <Grid item xs={12} sm={2} sx={styles.descriptionHead}>
                <CardMedia
                    sx={styles.cardMedia}
                    component={'img'}
                    image={`https://dedstudio.com.br/bejobs/images/vagas/${data.imagem}`} />
            </Grid>
            <Grid item xs={12} sm={4}>
                <Typography>
                    {data.profissao}
                </Typography>
                <Typography variant="h2">
                    {data.titulo}
                </Typography>
            </Grid>
            <Grid item xs={12} md={6} sx={styles.buttonBox}>
                <Button sx={styles.actionButton} component={Link} to={`/EditarVaga/${id}`} size="large" variant="contained" startIcon={<Edit />}>Editar</Button>

                <Button sx={styles.actionButton} size="large" variant="contained" color="error" startIcon={<Delete />} onClick={() => deleteJob()}>Excluir</Button>
                {
                    data.status === '1' &&
                    <Button sx={styles.actionButton} component={Link} to={`/VagaCandidaturas/${id}`} size="large" variant="contained" color="success" startIcon={<HowToReg />}>Ver candidaturas</Button>
                }
            </Grid>
            <Grid item xs={12} sx={styles.description}>
                <CorporateFareRounded color="primary" sx={styles.icons} />
                <Typography color={'primary'} sx={styles.descriptionText}>
                    {data.nome_empresa}
                </Typography>
                <LocationOnRounded color="primary" sx={styles.icons} />
                <Typography color={'primary'} sx={styles.descriptionText}>
                    {data.bairro + ', ' + data.cidade + ', ' + data.estado}
                </Typography>
                <AttachMoneyRounded color="primary" sx={styles.icons} />
                <Typography color={'primary'} sx={styles.descriptionText}>
                    {data.pagamento}
                </Typography>
            </Grid>
            <Grid item xs={12} sx={styles.contentDescription}>
                <Typography variant="h2" color="primary" mb={'1em'}>
                    Descrição da vaga
                </Typography>
                <Typography color="primary">
                    {data.descricao}
                </Typography>
            </Grid>
        </Grid>
    )
}