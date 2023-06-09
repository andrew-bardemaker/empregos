import { Facebook, Instagram, LinkedIn } from "@mui/icons-material";
import { Button, Grid, InputAdornment, MenuItem, Select, TextField, Typography } from "@mui/material";
import InputMask from 'react-input-mask';
import { useState } from "react";
import { useEffect } from "react";
import getUFCity from '../../components/getUFCity';
import api from "../../services/api";

export default function ContactAddress({ back, userType, handleChangeProgress, handleChangeContactInfo }) {

    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');

    const [instagram, setInstagram] = useState('');
    const [facebook, setFacebook] = useState('');
    const [linkedin, setLinkedin] = useState('');

    const [address, setAddress] = useState('');
    const [complement, setComplement] = useState('');
    const [number, setNumber] = useState('');
    const [location, setLocation] = useState('');
    const [CEP, setCEP] = useState('');

    const [state, setState] = useState(0);
    const [stateList, setStateList] = useState([]);

    const [city, setCity] = useState(0);
    const [cityList, setCityList] = useState([]);

    function verifyData() {
        if (email !== '' && phone !== 1 && address !== '' && complement !== '' && number !== '' && location !== '' && CEP !== '' && state !== '' && city !== '') {
            return true;
        } else {
            return false;
        };
    };

    function changeContactInfo() {

        if (verifyData()) {
            handleChangeContactInfo({
                email, phone, address, complement, number, location, CEP, state, city
            });
        } else {
            alert('Por favor, preencha todos os dados obrigatórios!');
        };
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
                }

                return temp_list;

            })
            .catch(err => {
                alert('Ocorreu um erro ao carregar sua página home!');
                console.log(err);
            });
    };

    const newCEP = CEP.replace('.', '').replace('-', '');

    useEffect(() => {
        if (CEP.length === 10) {
            api.get(`https://viacep.com.br/ws/${newCEP}/json/`)
                .then(res => {
                    setAddress(res.data.logradouro);
                    setLocation(res.data.bairro);
                    setCity(res.data.localidade);
                    setState(res.data.uf);
                })
                .catch(err => { console.log(err) });
        }
    }, [CEP]);

    useEffect(() => {
        getData();
    }, [state, city]);

    useEffect(() => {
        handleChangeProgress(50);
        getData();
    }, []);


    return (
        <Grid container spacing={1}>
            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Contato {userType === '1' && 'Profissional'}</Typography>
            </Grid>

            <Grid item xs={12} sm={3}>
                <InputMask
                    mask="(99) 9 9999-9999"
                    maskChar={null}
                    onChange={e => setPhone(e.target.value)}
                    value={phone}
                >
                    {() => <TextField
                        variant="outlined"
                        fullWidth
                        label='Telefone*'
                    />}
                </InputMask>
            </Grid>

            <Grid item xs={12} sm={6}>
                <TextField fullWidth label={"Email"} value={email} onChange={(e) => setEmail(e.target.value)} />
            </Grid>

            {
                userType === '0' && <>
                    <Grid item xs={12} sm={4}>
                        <TextField InputProps={{
                            startAdornment: (
                                <InputAdornment position="start">
                                    <Instagram />
                                </InputAdornment>
                            ),
                        }} fullWidth label="Instagram" value={instagram} onChange={(e) => setInstagram(e.target.value)} />
                    </Grid>

                    <Grid item xs={12} sm={4}>
                        <TextField InputProps={{
                            startAdornment: (
                                <InputAdornment position="start">
                                    <Facebook />
                                </InputAdornment>
                            ),
                        }} fullWidth label="Facebook" value={facebook} onChange={(e) => setFacebook(e.target.value)} />
                    </Grid>

                    <Grid item xs={12} sm={4}>
                        <TextField InputProps={{
                            startAdornment: (
                                <InputAdornment position="start">
                                    <LinkedIn />
                                </InputAdornment>
                            ),
                        }} fullWidth label="LinkedIn" value={linkedin} onChange={(e) => setLinkedin(e.target.value)} />
                    </Grid>
                </>
            }

            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Endereço {userType === '1' && 'Profissional'}</Typography>
            </Grid>

            <Grid item xs={6} sm={3}>
                <InputMask
                    mask="99.999-999"
                    maskChar={null}
                    onChange={e => setCEP(e.target.value)}
                    value={CEP}
                >
                    {() => <TextField
                        variant="outlined"
                        fullWidth
                        label='CEP*'
                    />}
                </InputMask>
            </Grid>

            <Grid item xs={6} >
                <TextField fullWidth label={"Endereço"} value={address} onChange={(e) => setAddress(e.target.value)} />
            </Grid>

            <Grid item xs={6} sm={2}>
                <TextField fullWidth label={"Número"} value={number} onChange={(e) => setNumber(e.target.value)} />
            </Grid>

            <Grid item xs={6} sm={2}>
                <TextField fullWidth label={"Complemento"} value={complement} onChange={(e) => setComplement(e.target.value)} />
            </Grid>

            <Grid item xs={6} sm={2}>
                <TextField fullWidth label={"Bairro"} value={location} onChange={(e) => setLocation(e.target.value)} />
            </Grid>

            <Grid item xs={6} sm={2}>
                <Select
                    fullWidth
                    value={state}
                    onChange={e => setState(e.target.value)}
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

            <Grid item xs={12} sx={{
                display: 'flex',
                justifyContent: 'space-between'
            }}>
                <Button onClick={() => back()}>
                    Voltar
                </Button>
                <Button size="large" variant="contained" onClick={() => changeContactInfo()}>
                    Avançar
                </Button>
            </Grid>
        </Grid>
    )
}