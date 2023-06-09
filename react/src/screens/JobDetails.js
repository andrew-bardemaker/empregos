import { Add, ArrowBackRounded, AttachMoneyRounded, CorporateFareRounded, Delete, HowToReg, LocationOnRounded, Work } from "@mui/icons-material";
import { Button, Card, CardMedia, CircularProgress, Grid, Typography } from "@mui/material";
import { useContext, useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import AuthContext from "../contexts/AuthProvider";
import api from "../services/api";

export default function JobDetails() {

    const { id } = useParams();

    const { userData } = useContext(AuthContext);

    const payload = {
        id_vaga: id,
        id_usuario: userData?.id
    };

    const [isLoading, setIsLoading] = useState(false);

    const [data, setData] = useState({});
    const [applied, setApplied] = useState(false);

    function handleApply() {

        setIsLoading(true);

        api.post('candidato-cadastro-vaga.php', payload)
            .then(res => {
                if (res.data.success) {
                    setApplied(res.data.result);
                    setIsLoading(false);
                    verifyApplication();
                } else {
                    alert('Ocorreu um erro ao verificar sua candidatura!');
                    setIsLoading(false);
                };
            })
            .catch(err => {
                alert('Ocorreu um erro ao verificar sua candidatura!');
                console.log(err);
                setIsLoading(false);
            });
    };

    function cancelApply() {
        api.post('candidato-cancelar.php', payload)
            .then(res => {
                if (res.data.success) {
                    verifyApplication();
                } else {
                    alert('Ocorreu um erro ao carregar sua página home!');

                };
            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    }

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

    function verifyApplication() {
        api.post('verifica-candidatura.php', payload)
            .then(res => {
                if (res.data.success) {
                    setApplied(res.data.result);
                } else {
                    alert('Ocorreu um erro ao carregar sua página home!');

                };
            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    };

    useEffect(() => {
        refreshData();
        userData && verifyApplication();
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

    function ApplyButton() {
        if (userData?.tipo_usuario === '1') {
            return (
                <Button component={Link} to="/CadastrarVagas" startIcon={<Add />} variant="contained">
                    Cadastrar vagas como essa!
                </Button>
            );
        } else if (userData?.tipo_usuario === '0' && !applied) {
            return (
                <Button disabled={isLoading} onClick={() => handleApply()} startIcon={<Work />} variant="contained">
                    {isLoading ? <CircularProgress /> : "Candidatar-se"}
                </Button>
            );
        } else if (userData?.tipo_usuario === '0' && applied) {
            return (
                <Button startIcon={<Delete />} onClick={() => cancelApply()} color="error" variant="contained">
                    Cancelar inscrição
                </Button>
            );
        } else {
            return (
                <Button component={Link} to="/Login" startIcon={<Work />} variant="contained">
                    Inscreva-se para concorrer à vaga!
                </Button>
            );
        };
    };

    return (
        <Grid component={Card} container sx={styles.grid}>
            <Grid item xs={12} m={'.5em 0'}>
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
                <ApplyButton />
            </Grid>
            <Grid item xs={12} sx={styles.description}>
                <CorporateFareRounded color="primary" sx={styles.icons} />
                <Typography color={'primary'} sx={styles.descriptionText}>
                    {data.nome_empresa}
                </Typography>
                <LocationOnRounded color="primary" sx={styles.icons} />
                <Typography color={'primary'} sx={styles.descriptionText}>
                    {data.bairro +', ' + data.cidade + ', ' + data.estado}
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
    );
};