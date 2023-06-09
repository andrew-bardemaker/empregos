import { AddLink, Work } from "@mui/icons-material";
import { Button, Checkbox, Chip, FormControl, Grid, Input, InputAdornment, InputLabel, MenuItem, Select, TextField, Typography } from "@mui/material";
import { AdapterMoment } from '@mui/x-date-pickers/AdapterMoment';
import 'moment/locale/pt-br';
import { LocalizationProvider, MobileDatePicker } from "@mui/x-date-pickers";
import { useCallback, useEffect } from "react";
import { useState } from "react";
import InputMask from 'react-input-mask';
import { diamondPlanProfessions } from "../../data/professions";

export default function PersonalData({ back, handleChangeProgress, handleChangeUserData, userType }) {

    const [title, setTitle] = useState('');

    const [nome, setNome] = useState('');

    const [rSocial, setRSocial] = useState('');

    const [nascimento, setNascimento] = useState('');
    const [sexo, setSexo] = useState('');

    const [CPF, setCPF] = useState(null);
    const [RG, setRG] = useState('');

    const [estrangeiro, setEstrangeiro] = useState(false);
    const [docInternacional, setDocInternacional] = useState('');

    const [CNPJ, setCNPJ] = useState('');

    const [portfoil, setPortfoil] = useState('');

    const [profession, setProfession] = useState('');

    const [userProfession, setUserProfession] = useState([]);

    const [senha, setSenha] = useState('');
    const [confSenha, setConfSenha] = useState('');

    function changeUserData() {

        if (verifyData()) {
            if (senha === confSenha) {
                if (userType === '0') {
                    var basicData = {
                        nome,
                        nascimento,
                        sexo,
                        profession
                    };

                    if (estrangeiro) {
                        var data = {
                            ...basicData,
                            estrangeiro,
                            docInternacional
                        };
                    } else {
                        var data = {
                            ...basicData,
                            CPF,
                            RG
                        };
                    };

                } else {
                    var data = {
                        nome,
                        razao_social: rSocial,
                        CNPJ,
                        senha
                    };
                };
                handleChangeUserData(data);
            } else {
                alert('Sua senha e confirmação de senha devem ser iguais!');
            };
        } else {
            alert('Por favor, preencha todos os dados obrigatórios!');
        };
    };

    function verifyData() {
        if (nome !== '' && (userType === '0' ? (nascimento !== '' && sexo !== '' && profession !== '' && (estrangeiro ? (docInternacional !== '') : (CPF !== '' & RG !== ''))) : (rSocial !== '' && CNPJ !== ''))) {
            return true;
        } else {
            return false;
        };
    };

    useEffect(() => {
        handleChangeProgress(25);
        setTitle(userType === '0' ? 'Pessoais' : 'Empresariais');
    }, []);

    return (
        <Grid container spacing={1}>
            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Informações {title}</Typography>
            </Grid>

            <Grid item xs={12} sm={6}>
                <TextField fullWidth label={userType === '1' ? 'Nome Fantasia*' : 'Nome*'} value={nome} onChange={(e) => setNome(e.target.value)} />
            </Grid>

            {
                userType === '1' &&
                <>
                    <Grid item xs={12} sm={6}>
                        <TextField fullWidth label="Razão Social*" value={rSocial} onChange={(e) => setRSocial(e.target.value)} />
                    </Grid>
                    <Grid item xs={12} sm={6}>
                        <InputMask
                            mask="99.999.999/9999-99"
                            maskChar={null}
                            onChange={e => setCNPJ(e.target.value)}
                            value={CNPJ}
                        >
                            {() => <TextField
                                variant="outlined"
                                fullWidth
                                label='CNPJ*'
                            />}
                        </InputMask>
                    </Grid>
                    <Grid item xs={12}>
                        <Typography sx={{ mt: '1em' }} variant='h2'>Senha</Typography>
                    </Grid>
                    <Grid item xs={12} sm={6}>
                        <TextField type='password' fullWidth label="Senha*" value={senha} onChange={(e) => setSenha(e.target.value)} />
                    </Grid>
                    <Grid item xs={12} sm={6}>
                        <TextField type='password' fullWidth label="Confirmar Senha*" value={confSenha} onChange={(e) => setConfSenha(e.target.value)} />
                    </Grid>
                </>
            }

            {
                userType === '0' &&
                <>
                    <Grid item xs={6} sm={3}>
                        <LocalizationProvider dateAdapter={AdapterMoment} adapterLocale={'pt-br'}>
                            <MobileDatePicker
                                label="Data de Nascimento"
                                sx={{ width: '100%' }}
                                value={nascimento}
                                onChange={(newValue) => {
                                    setNascimento(newValue);
                                }}
                                renderInput={(params) => <TextField {...params} />}
                            />
                        </LocalizationProvider>
                    </Grid>

                    <Grid item xs={6} sm={3}>
                        <FormControl fullWidth>
                            <InputLabel>Sexo</InputLabel>
                            <Select
                                fullWidth
                                value={sexo}
                                label="Sexo*"
                                onChange={e => setSexo(e.target.value)}
                            >
                                <MenuItem value={''}>- Selecionar Sexo -</MenuItem>
                                <MenuItem value={'M'}>Masculino</MenuItem>
                                <MenuItem value={'F'}>Feminino</MenuItem>
                                <MenuItem value={'No'}>Não responder</MenuItem>
                            </Select>
                        </FormControl>
                    </Grid>

                    <Grid item xs={12} sm={3} sx={{ display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                        <Checkbox checked={estrangeiro} onChange={() => setEstrangeiro(!estrangeiro)} />
                        <Typography>
                            Documento Estrangeiro
                        </Typography>

                    </Grid>

                    {
                        estrangeiro ?
                            <Grid item xs={12} sm={6}>
                                <TextField fullWidth label="Documento" value={docInternacional} onChange={(e) => setDocInternacional(e.target.value)} />
                            </Grid>
                            :
                            <>
                                <Grid item xs={12} sm={3}>
                                    <InputMask
                                        mask="999.999.999-99"
                                        maskChar={null}
                                        onChange={e => setCPF(e.target.value)}
                                        value={CPF}
                                    >
                                        {() => <TextField
                                            variant="outlined"
                                            fullWidth
                                            label='CPF*'
                                        />}
                                    </InputMask>
                                </Grid>

                                <Grid item xs={12} sm={3}>
                                    <InputMask
                                        mask="9999999999"
                                        maskChar={null}
                                        onChange={e => setRG(e.target.value)}
                                        value={RG}
                                    >
                                        {() => <TextField
                                            variant="outlined"
                                            fullWidth
                                            label='RG*'
                                        />}
                                    </InputMask>
                                </Grid>
                            </>
                    }

                    <Grid item xs={12}>
                        <Typography sx={{ mt: '1em' }} variant='h2'>Portfólio</Typography>
                    </Grid>

                    <Grid item xs={12} sm={6}>
                        <TextField fullWidth label="Portfólio" value={portfoil} onChange={(e) => setPortfoil(e.target.value)} />
                    </Grid>

                    <Grid item xs={12}>
                        <Typography sx={{ mt: '1em' }} variant='h2'>Especialidades</Typography>
                    </Grid>

                    <Grid item xs={12}>
                        {
                            userProfession.length > 0 ?
                                userProfession?.map((obj, index) => {
                                    return <Chip color="primary" sx={{ m: '.25em' }} label={obj} variant="filled" onDelete={() => setUserProfession(oldValue => oldValue.splice(index))} />
                                })
                                :
                                <Chip color="error" variant="filled" label={'Você ainda não incluiu especialidades'} />
                        }
                    </Grid>

                    <Grid item xs={6} sm={3}>
                        <FormControl fullWidth>
                            <Select
                                value={''}
                                onChange={(e) => setUserProfession(oldData => [...oldData, e.target.value])}
                                fullWidth
                                displayEmpty
                                MenuProps={{ PaperProps: { sx: { maxHeight: 225 } } }}
                                inputProps={{
                                    'aria-label': 'Without label'
                                }}
                            >
                                <MenuItem value={''}>- Adicionar especialidade -</MenuItem>
                                {
                                    diamondPlanProfessions.map((obj, index) => {
                                        return !userProfession.includes(obj) && (
                                            <MenuItem key={index} value={obj}>{obj}</MenuItem>
                                        );
                                    })
                                }
                            </Select>
                        </FormControl>
                    </Grid>
                </>

            }

            <Grid item xs={12} sx={{
                display: 'flex',
                justifyContent: 'space-between'
            }}>
                <Button onClick={() => back()}>
                    Voltar
                </Button>
                <Button size="large" variant="contained" onClick={() => changeUserData()}>
                    Avançar
                </Button>
            </Grid>
        </Grid>
    );
};