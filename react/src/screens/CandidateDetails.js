import {
    React,
    useContext,
    useEffect,
    useState,
} from 'react';

import { Box, Button, Card, CardMedia, createTheme, FormControl, Grid, InputAdornment, InputLabel, MenuItem, Select, TextField, Typography } from '@mui/material';
import { AddLink, ArrowBackRounded, Delete, Edit, Facebook, Instagram, LinkedIn } from '@mui/icons-material';
import AuthContext from '../contexts/AuthProvider';
import { Link, useParams } from 'react-router-dom';
import api from '../services/api';

export default function EditProfile() {

    const [data, setData] = useState('');

    const [nome, setNome] = useState('');
    const [celular, setCelular] = useState('');
    const [nascimento, setNascimento] = useState('');
    const [sexo, setSexo] = useState('');
    const [email, setEmail] = useState('');
    const [CPF, setCPF] = useState('');
    const [RG, setRG] = useState('');

    const [portfolio, setPortfolio] = useState('');

    const [instagram, setFacebook] = useState('');
    const [facebook, setInstagram] = useState('');
    const [linkedin, setLinkedin] = useState('');

    const [endereco, setEndereco] = useState('');
    const [complemento, setComplemento] = useState('');
    const [number, setNumber] = useState('');
    const [bairro, setBairro] = useState('');
    const [CEP, setCEP] = useState('');
    const [cidade, setCidade] = useState('');
    const [estado, setEstado] = useState('');
    const [userImage, setUserImage] = useState('default.jpg');

    const { id } = useParams();

    useEffect(() => {
        api.post('detalhes-usuario.php', {
            id_usuario: id
        })
            .then(res => {
                if (res.data.success) {

                    var user_data = res.data.detalhes[0];

                    setNome(user_data.nome);
                    setCelular(user_data.celular);
                    setNascimento(user_data.nascimento);
                    setSexo(user_data.sexo);
                    setEmail(user_data.email);

                    setCPF(user_data.cpf);
                    setRG(user_data.rg);

                    setPortfolio(user_data.portfolio);

                    setFacebook(user_data.facebook);
                    setInstagram(user_data.instagram);
                    setLinkedin(user_data.linkedin);

                    setEndereco(user_data.endereco);
                    setComplemento(user_data.complemento);
                    setNumber(user_data.numero);
                    setBairro(user_data.bairro);
                    setCEP(user_data.cep);
                    setCidade(user_data.cidade);
                    setEstado(user_data.estado);

                    setUserImage(user_data.imagem);
                } else {
                    alert('Houve um erro ao carregar as informações deste usuário!');
                };
            })
            .catch(err => {
                alert('Erro de conexão! Tente novamente mais tarde.');
                console.log(err);
            })
    }, []);

    const styles = createTheme({
        centralize: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
        },
        userProfile: {
            m: 'auto',
            height: 150,
            width: 150,
            borderRadius: '50%'
        },
        userProfileBox: {
            m: '2em 1em',
            width: '100%',
            maxWidth: '1280px',
            p: '1em'
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

    return (
        <Box sx={styles.centralize}>
            <Grid container component={Card} spacing={1} sx={styles.userProfileBox}>
                <Grid item xs={12} m={'1em 0'}>
                    <Button component={Link} to={"/MinhasVagas"} startIcon={<ArrowBackRounded />}>
                        Voltar para minhas vagas
                    </Button>
                </Grid>
                <Grid item xs={12}>
                    <CardMedia sx={styles.userProfile} image={userImage} />
                </Grid>

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Informações Pessoais</Typography>
                </Grid>

                <Grid item xs={12} sm={6}>
                    <TextField disabled fullWidth label="Nome" value={nome} onChange={(e) => setNome(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Nascimento" value={nascimento} onChange={(e) => setNascimento(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <FormControl>
                        <InputLabel>Sexo</InputLabel>
                        <Select disabled
                            value={sexo}
                            label="Sexo"
                            onChange={e => setSexo(e.target.value)}
                        >
                            <MenuItem value={'M'}>Masculino</MenuItem>
                            <MenuItem value={'F'}>Feminino</MenuItem>
                            <MenuItem value={'No'}>Não responder</MenuItem>
                        </Select>
                    </FormControl>
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="CPF" value={CPF} onChange={(e) => setCPF(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="RG" value={RG} onChange={(e) => setRG(e.target.value)} />
                </Grid>

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Galeria</Typography>
                </Grid>

                {[...Array(6)].map(() => {
                    return (
                        <Grid item xs={4} sm={2}>
                            <CardMedia component={'img'} image='https://placehold.jp/250x250.png' />
                        </Grid>
                    )
                })}

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Informações Profissionais</Typography>
                </Grid>

                <Grid item xs={12} sm={6}>
                    <TextField disabled InputProps={{
                        startAdornment: (
                            <InputAdornment position="start">
                                <AddLink />
                            </InputAdornment>
                        ),
                    }} fullWidth label="Portfólio" value={portfolio} onChange={(e) => setPortfolio(e.target.value)} />
                </Grid>

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Redes sociais</Typography>
                </Grid>

                <Grid item xs={12} sm={4}>
                    <TextField disabled InputProps={{
                        startAdornment: (
                            <InputAdornment position="start">
                                <Instagram />
                            </InputAdornment>
                        ),
                    }} fullWidth label="Instagram" value={instagram} onChange={(e) => setInstagram(e.target.value)} />
                </Grid>

                <Grid item xs={12} sm={4}>
                    <TextField disabled InputProps={{
                        startAdornment: (
                            <InputAdornment position="start">
                                <Facebook />
                            </InputAdornment>
                        ),
                    }} fullWidth label="Facebook" value={facebook} onChange={(e) => setFacebook(e.target.value)} />
                </Grid>

                <Grid item xs={12} sm={4}>
                    <TextField disabled InputProps={{
                        startAdornment: (
                            <InputAdornment position="start">
                                <LinkedIn />
                            </InputAdornment>
                        ),
                    }} fullWidth label="LinkedIn" value={linkedin} onChange={(e) => setLinkedin(e.target.value)} />
                </Grid>

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Endereço</Typography>
                </Grid>

                <Grid item xs={12} sm={6}>
                    <TextField disabled fullWidth label="Endereço residencial" value={endereco} onChange={(e) => setEndereco(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Número" value={number} onChange={(e) => setNumber(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Complemento" value={complemento} onChange={(e) => setComplemento(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Bairro" value={bairro} onChange={(e) => setBairro(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="CEP" value={CEP} onChange={(e) => setCEP(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Cidade" value={cidade} onChange={(e) => setCidade(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={2}>
                    <TextField disabled fullWidth label="Estado" value={estado} onChange={(e) => setEstado(e.target.value)} />
                </Grid>

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Contato</Typography>
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Celular" value={celular} onChange={(e) => setCelular(e.target.value)} />
                </Grid>

                <Grid item xs={6} sm={3}>
                    <TextField disabled fullWidth label="Email 1" value={email} onChange={(e) => setEmail(e.target.value)} />
                </Grid>

            </Grid>
        </Box>
    )
};