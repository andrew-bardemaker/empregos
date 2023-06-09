import { AttachMoneyRounded, LocationOnRounded, MedicalServicesRounded, Search, WorkOff } from "@mui/icons-material";
import { Box, Button, Card, CardMedia, Grid, MenuItem, Select, Typography } from "@mui/material";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import getUFCity from "../components/getUFCity";
import { diamondPlanProfessions } from "../data/professions";
import api from "../services/api";

export default function Home() {

    const [data, setData] = useState([]);
    const [total, setTotal] = useState(0);

    const [state, setState] = useState(0);
    const [stateList, setStateList] = useState([]);
    const [city, setCity] = useState(0);
    const [cityList, setCityList] = useState([]);
    const [location, setLocation] = useState(0);
    const [locationList, setLocationList] = useState([]);

    const [profession, setProfession] = useState(0);

    function refreshData() {

        var estado = null;
        var cidade = null;
        var bairro = null;
        var profissao = null;

        if (location !== 0) {
            bairro = location;
        };

        if (city !== 0) {
            cidade = city;
        };

        if (state !== 0) {
            estado = state;
        };

        if (profession !== 0) {
            profissao = profession;
        };

        api.post('vagas.php', {
            bairro,
            cidade,
            estado,
            profissao
        })
            .then(res => {
                if (res.data.success) {
                    setData(res.data.vagas);
                    setTotal(res.data.total);
                } else {
                    alert('Ocorreu um erro ao carregar sua página home!');
                };
            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    };

    function getData() {
        getUFCity(state !== 0 && state)
            .then(res => {
                let res_list = res.data;
                let temp_list = [];
                for (let i = 0; i < res_list.length; i++) {

                    if (state !== 0) {
                        temp_list?.push(res_list[i].nome);
                    } else {
                        temp_list?.push(res_list[i].sigla);
                    };
                }

                if (state === 0) {
                    setStateList(temp_list);
                } else {
                    setCityList(temp_list);
                };

                return temp_list;

            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    };

    function getLocation() {
        let cidade = null;

        if (city !== 0) {
            cidade = city;
        };

        api.post('bairro-por-cidade.php', { cidade })
            .then(res => {
                if (res.data.success) {
                    setLocationList(res.data.bairros);
                } else {
                    alert('Houve um erro ao carregar os bairros!');
                }
            })
            .catch(err => {
                alert('Houve um erro ao carregar os bairros!');
                console.log(err);
            });
    };

    useEffect(() => {
        refreshData();
        getData();

    }, [state, city, location, profession]);

    useEffect(() => {
        getLocation();
    }, [city])

    const styles = {
        centralizer: {
            maxWidth: '1280px',
            p: '1em'
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
            width: {
                xs: '5em',
                sm: '7em'
            },
            height: {
                xs: '5em',
                sm: '7em'
            }
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
        noJob: {
            p: '2em',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column'
        },
    };

    return (
        <Grid container sx={styles.centralizer} spacing={1}>
            <Grid item xs={12}>
                <Typography variant="h1" color='white'>
                    Encontrar vagas
                </Typography>
            </Grid>
            <Grid item xs={12} sm={3}>
                <Card sx={styles.searchContent}>
                    <Typography fontWeight={600} variant="h2" sx={styles.search}>
                        {total} vagas encontradas
                    </Typography>
                    <Typography fontWeight={600} sx={styles.search}>
                        Por localidade
                    </Typography>
                    <Select
                        fullWidth
                        value={state}
                        onChange={e => setState(e.target.value)}
                        sx={styles.search}
                        MenuProps={{ PaperProps: { sx: { maxHeight: 225 } } }}
                    >
                        <MenuItem value={0}>- Todos os estados -</MenuItem>
                        {
                            stateList.map((obj, index) => {
                                return <MenuItem key={index} value={obj}>{obj}</MenuItem>
                            })
                        }
                    </Select>
                    <Select
                        fullWidth
                        value={city}
                        onChange={e => setCity(e.target.value)}
                        disabled={state === 0}
                        sx={styles.search}
                        MenuProps={{ PaperProps: { sx: { maxHeight: 225 } } }}
                    >
                        <MenuItem value={0}>- Todas as cidades -</MenuItem>
                        {
                            cityList.map((obj, index) => {
                                return <MenuItem key={index} value={obj}>{obj}</MenuItem>
                            })
                        }
                    </Select>
                    <Select
                        fullWidth
                        value={location}
                        onChange={e => setLocation(e.target.value)}
                        disabled={city === 0}
                        sx={styles.search}
                    >
                        <MenuItem value={0}>- Todas os bairros -</MenuItem>
                        {
                            locationList?.map((obj, index) => {
                                return <MenuItem key={index} value={obj}>{obj}</MenuItem>
                            })
                        }
                    </Select>
                    <Typography fontWeight={600} sx={styles.search}>
                        Por especialidade
                    </Typography>
                    <Select
                        fullWidth
                        value={profession}
                        onChange={e => setProfession(e.target.value)}
                        MenuProps={{ PaperProps: { sx: { maxHeight: 225 } } }}
                        sx={styles.search}
                    >
                        <MenuItem value={0}>- Selecione a Profissão -</MenuItem>
                        {
                            diamondPlanProfessions?.map((obj, index) => {
                                return <MenuItem key={index} value={obj}>{obj}</MenuItem>
                            })
                        }
                    </Select>
                </Card>
            </Grid>
            <Grid item xs={12} sm={9}>
                <Card component={Grid} container p={'1em'}>
                    {
                        data ?
                            data.map((job, index) => {
                                return (
                                    <Grid item key={index} component={Card} xs={12} sx={styles.jobItem}>
                                        <Button component={Link} to={`/VagaDetalhes/${job.id_vaga}`} sx={styles.jobButton}>
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
                                                        {job.cidade + ', ' + job.estado}
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
                            <Grid item xs={12} sx={styles.noJob}>
                                <WorkOff fontSize="large" sx={{ color: 'red' }} />
                                <Typography variant="h2" mt={'16px'} textAlign="center">
                                    Ops! Não foram encontradas vagas para esta região.
                                </Typography>
                            </Grid>
                    }
                </Card>
            </Grid>
        </Grid>
    )
}