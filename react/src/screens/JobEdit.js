import {
    React,
    useEffect,
    useState,
} from 'react';

import { Box, Button, Card, CardMedia, createTheme, FormControl, FormHelperText, Grid, MenuItem, Select, TextField, Typography } from '@mui/material';
import { Add, AddPhotoAlternate, RemoveRedEye, Work } from '@mui/icons-material';
import api from '../services/api';
import { useContext } from 'react';
import AuthContext from '../contexts/AuthProvider';
import { Link, useParams } from 'react-router-dom';
import { paymentMethods } from '../data/payment';
import getUFCity from '../components/getUFCity';
import { diamondPlanProfessions, quartzPlanProfessions } from '../data/professions';

export default function JobEdit() {

    const [message, setMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    const [newJob, setNewJob] = useState('');

    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');

    const [profession, setProfession] = useState('');
    const [professionList, setProfessionList] = useState([]);

    const [payment, setPayment] = useState('');

    const [img, setImg] = useState('');
    const [imgPreview, setImgPreview] = useState(null);

    const [city, setCity] = useState(0);
    const [cityList, setCityList] = useState([]);

    const [state, setState] = useState(0);
    const [stateList, setStateList] = useState([]);

    const [location, setLocation] = useState('');

    const { userData } = useContext(AuthContext);

    const { id } = useParams();

    function resetFields() {
        setTitle('');
        setDescription('');
        setProfession(0);
        setPayment('');
        setLocation('');
        setCity(0)
        setState(0);
        setImg('');
        setImgPreview(null);
    };

    function editJob() {
        setErrorMessage('');
        setMessage('');

        if (title !== '' && description !== '' && profession !== '' && payment !== '' && location !== '' && city !== '' && state !== '') {
            const data = {
                id: id,
                id_empresa: userData.id,
                titulo: title,
                descricao: description,
                profissao: profession,
                pagamento: payment,
                bairro: location,
                cidade: city,
                estado: state,
                imagem: img
            };

            api.post('vagas-editar.php', data)
                .then(res => {

                    var response = res.data;

                    if (response.success) {
                        setMessage(response.message);
                        setNewJob(response.id_vaga);
                        resetFields();
                        setErrorMessage('');
                    } else {
                        setErrorMessage(response.message);
                    };
                })
                .catch(error => {
                    setErrorMessage('Houve um erro! Contate o administrador!');
                    console.log(error);
                });
        } else {
            setErrorMessage("Para cadastrar uma nova vaga, primeiro você deve preencher todos os campos!");
        };
    };

    function onFileChange(e) {
        let files = e.target.files;

        setImgPreview(URL.createObjectURL(files[0]));

        let fileReader = new FileReader();

        fileReader.readAsDataURL(files[0]);
        fileReader.onload = (event) => {
            setImg(event.target.result)
        };
    };

    function getJobDetails() {
        api.post('vagas-detalhes.php', { id })
            .then(res => {
                if (res.data.success) {

                    var data = res.data.vaga[0];

                    setTitle(data.titulo);
                    setDescription(data.descricao);
                    setProfession(data.profissao);
                    setPayment(data.pagamento);
                    setLocation(data.bairro);
                    setCity(data.cidade);
                    setState(data.estado);
                    setImgPreview(`https://dedstudio.com.br/bejobs/images/vagas/${data.imagem}`)

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

    const styles = createTheme({
        centralize: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'start',
            p: '2em 1em 5em 1em ',
        },
        userProfile: {
            m: 'auto',
            height: 150,
            width: 150,
            borderRadius: '50%'
        },
        userProfileBox: {
            p: '1em',
            m: '2em 1em',
            width: '100%',
            maxWidth: '1280px'
        },
        checkboxContainer: {
            display: 'flex',
            flexDirection: "row",
            alignItems: "center",
        },
        label: {
            margin: 10,
        },
        editButton: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'row',
            width: '100%',
            marginTop: '1.5em'
        },
        textField: {
            mt: '1.5em',
            width: '100%'
        }
    });

    useEffect(() => {
        getJobDetails();
    }, []);

    useEffect(() => {
        getData();
        userData.plano_empresa === '0' ?
            setProfessionList(quartzPlanProfessions)
            :
            setProfessionList(diamondPlanProfessions);
    }, [state, city]);

    return (
        <Grid component={Card} container spacing={1} sx={styles.userProfileBox}>
            <Grid item xs={12}>
                <Typography variant='h1'>
                    Editar vaga
                </Typography>
            </Grid>

            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Informações da Vaga</Typography>
            </Grid>

            <Grid item xs={12} sm={6}>
                <TextField fullWidth label="Título" value={title} onChange={(e) => setTitle(e.target.value)} />
            </Grid>

            <Grid item xs={12}>
                <TextField fullWidth label="Descrição" value={description}
                    multiline rows={4} onChange={(e) => setDescription(e.target.value)} />
            </Grid>

            <Grid item xs={6} sm={3}>
                <Select
                    value={profession}
                    onChange={(e) => setProfession(e.target.value)}
                    fullWidth
                    displayEmpty
                    inputProps={{ 'aria-label': 'Without label' }}
                    MenuProps={{ PaperProps: { sx: { maxHeight: 225 } } }}
                >
                    <MenuItem value={''}>- Selecione a Profissão -</MenuItem>
                    {
                        professionList.map((obj, index) => {
                            return <MenuItem key={index} value={obj}>{obj}</MenuItem>
                        })
                    }
                </Select>
            </Grid>

            <Grid item xs={6} sm={3}>
                <FormControl>
                    <Select
                        value={payment}
                        onChange={(e) => setPayment(e.target.value)}
                        fullWidth
                    >
                        <MenuItem value={''}>- Selecione a forma de contrato -</MenuItem>
                        {
                            paymentMethods.map((obj, index) => {
                                return <MenuItem key={index} value={obj}>{obj}</MenuItem>
                            })
                        }
                    </Select>
                    <FormHelperText>CLT se trata na Consolidação das Leis Trabalhistas (Carteira) e PJ, Pessoa Jurídica, sendo MEI, ME, Eireli e outros</FormHelperText>
                </FormControl>
            </Grid>

            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Endereço</Typography>
            </Grid>

            <Grid item xs={6} sm={2}>
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
            </Grid>

            <Grid item xs={6} sm={4}>
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
            </Grid>

            <Grid item xs={6} sm={3}>
                <TextField fullWidth label="Bairro" value={location} onChange={(e) => setLocation(e.target.value)} />
            </Grid>


            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Imagem</Typography>
            </Grid>

            <Grid item xs={12} sm={6}>
                <input
                    accept="image/*"
                    type="file"
                    id="select-image"
                    style={{ display: "none" }}
                    onChange={(e) => onFileChange(e)}
                />

                {
                    imgPreview && (
                        <CardMedia component={'img'} image={imgPreview} sx={{ width: '15em', height: '15em', m: 'auto', borderRadius: '20% 20% 20% 20%' }} />
                    )
                }

                <label htmlFor="select-image">
                    <Button fullWidth sx={{ mt: '1em' }} startIcon={<AddPhotoAlternate />} variant="contained" color="primary" component="span">
                        Adicionar imagem
                    </Button>
                </label>
            </Grid>

            <Grid item xs={12}>
                <Button startIcon={<Work />} color="primary" variant="contained" fullWidth sx={{ mt: '1.5em' }} onClick={() => editJob()}>
                    Editar vaga
                </Button>
                {message && <Typography color={'green'} mt="1em">{message}</Typography>}
                {errorMessage && <Typography color={'red'} mt="1em">{errorMessage}</Typography>}
                {newJob && <Button startIcon={<RemoveRedEye />} variant='contained' component={Link} to={`/VagaDetalhes/${newJob}`} mt="1em">Visualizar vaga</Button>}
            </Grid>
        </Grid>
    )
};