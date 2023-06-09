import {
    React,
    useContext,
    useEffect,
    useState,
} from 'react';

import { Box, Button, Card, CardMedia, createTheme, FormControl, Grid, IconButton, InputAdornment, InputLabel, MenuItem, Select, TextField, Typography } from '@mui/material';
import { AddLink, Delete, Edit, Facebook, Instagram, LinkedIn, Upload } from '@mui/icons-material';
import AuthContext from '../contexts/AuthProvider';

export default function EditProfile() {

    const [id, setId] = useState('');

    const [message, setMessage] = useState('');

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
    const [userImage, setUserImage] = useState('https://dedstudio.com.br/bejobs/images/perfis/default.jpg');
    const [senha, setSenha] = useState('');
    const [conf_senha, setConfSenha] = useState('');

    const { userData } = useContext(AuthContext);

    useEffect(() => {
        setNome(userData.nome);
        setCelular(userData.numero);
        setNascimento(userData.nascimento);
        setSexo(userData.sexo);
        setEmail(userData.email);
        setCPF(userData.cpf);
        setRG(userData.rg);
        setEndereco(userData.logradouro);
        setComplemento(userData.complemento);
        setNumber(userData.numero);
        setBairro(userData.bairro);
        setCEP(userData.cep);
        setCidade(userData.cidade);
        setEstado(userData.estado);
        setPortfolio(userData.portfolio);
        setInstagram(userData.instagram);
        setFacebook(userData.facebook);
        setLinkedin(userData.linkedin);
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
            p: '2em 1em',
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
        },
        uploadBox: {
            mt: '1em',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column'
        },
        uploadButton: {
            width: '8em',
            height: '8em'
        },
        uploadText: {
            fontWeight: '700',
            textAlign: 'center',
            m: '1em'
        }
    });

    return (
        <Box sx={styles.centralize}>
            <Grid component={Card} container spacing={1} sx={styles.userProfileBox}>
                <Grid item xs={12}>
                    <CardMedia sx={styles.userProfile} image={userImage} />
                </Grid>

                <Grid item xs={12} sm={6}>
                    <Button disabled
                        fullWidth
                        color="primary" variant="contained"
                        startIcon={<Edit />}
                        onClick={() => { }}>
                        Editar imagem
                    </Button>
                </Grid>

                <Grid item xs={12} sm={6}>
                    <Button disabled
                        fullWidth
                        color="primary" variant="contained"
                        startIcon={<Delete />}
                        onClick={() => { }}>
                        Remover imagem
                    </Button>
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
                        <Select
                            disabled
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

                <Grid component={Card} item xs={12} sx={styles.uploadBox}>
                    <IconButton disabled>
                        <Upload sx={styles.uploadButton} color="primary" />
                    </IconButton>
                    <Typography sx={styles.uploadText}>
                        Insira aqui, até 6 imagens relacionadas à você e seu trabalho
                    </Typography>
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

                <Grid item xs={12}>
                    <Typography sx={{ mt: '1em' }} variant='h2'>Senha</Typography>
                </Grid>

                <Grid item xs={12} sm={6}>
                    <TextField disabled fullWidth label="Senha" value={senha} onChange={(e) => setSenha(e.target.value)} />
                </Grid>

                <Grid item xs={12} sm={6}>
                    <TextField disabled fullWidth label="Confirmar Senha" value={conf_senha} onChange={(e) => setConfSenha(e.target.value)} />
                </Grid>

                <Grid item xs={12} sm={6}>
                    <Button disabled startIcon={<Edit />} color="primary" variant="contained" fullWidth sx={{ mt: '1.5em' }} onClick={() => { }}>
                        Editar cadastro
                    </Button>
                    {message && <Typography>{message}</Typography>}
                </Grid>
            </Grid>
        </Box>
    )
};