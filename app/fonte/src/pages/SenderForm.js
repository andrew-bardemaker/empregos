import * as React from 'react';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import TextField from '@mui/material/TextField';
import FormControlLabel from '@mui/material/FormControlLabel';
import Checkbox from '@mui/material/Checkbox';
import { MenuItem } from '@material-ui/core';
import DeliveryContext from '../context/DeliveryProvider';

export default function SenderForm() {


    const {
        modality, setModality,
        senderName, setSenderName,
        senderEmail, setSenderEmail,
        senderWhatsapp, setSenderWhatsapp,
        senderCep, setSenderCep,
        senderAdress, setSenderAdress,
        senderNumber, setSenderNumber,
        senderComplement, setSenderComplement,
        senderDistrict, setSenderDistrict,
        senderCity, setSenderCity,
        senderUf, setSenderUf,
        senderProduct, setSenderProduct,
        senderSaveAdress, setSenderSaveAdress
    } = React.useContext(DeliveryContext);

    return (
        <React.Fragment>
            <Typography variant="h6" gutterBottom>
                Informações - Remetente
            </Typography>
            <Typography variant="p" color="#ff8a00" gutterBottom>
                Insira o endereço de ORIGEM da encomenda
            </Typography>
            <Grid container spacing={3} paddingTop={2}>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Seu nome completo"
                        fullWidth
                        autoComplete="given-name"
                        variant="outlined"
                        value={senderName}
                        onChange={(e) => { setSenderName(e.target.value) }}
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Seu email"
                        fullWidth
                        autoComplete="family-name"
                        variant="outlined"
                        value={senderEmail}
                        onChange={e => setSenderEmail(e.target.value)}
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Seu Whatsapp (DDD)"
                        fullWidth
                        autoComplete="shipping address-line1"
                        variant="outlined"
                        value={senderWhatsapp}
                        onChange={e => setSenderWhatsapp(e.target.value)}
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="CEP"
                        fullWidth
                        autoComplete="shipping postal-code"
                        variant="outlined"
                        value={senderCep}
                        onChange={e => setSenderCep(e.target.value)}
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        label="Endereço"
                        fullWidth
                        autoComplete="shipping address-line2"
                        variant="outlined"
                        value={senderAdress}
                        onChange={e => setSenderAdress(e.target.value)}
                    />
                </Grid>
                <Grid item xs={6} sm={3}>
                    <TextField
                        label="Número"
                        fullWidth
                        autoComplete="shipping address-line2"
                        variant="outlined"
                        value={senderNumber}
                        onChange={e => setSenderNumber(e.target.value)}
                    />
                </Grid>
                <Grid item xs={6} sm={3}>
                    <TextField
                        label="Complemento"
                        fullWidth
                        autoComplete="shipping address-line2"
                        variant="outlined"
                        value={senderComplement}
                        onChange={e => setSenderComplement(e.target.value)}
                    />
                </Grid>
                <Grid item xs={6} sm={6}>
                    <TextField
                        label="Bairro"
                        fullWidth
                        autoComplete="shipping address-line2"
                        variant="outlined"
                        value={senderDistrict}
                        onChange={e => setSenderDistrict(e.target.value)}
                    />
                </Grid>
                <Grid item xs={4}>
                    <TextField
                        required
                        label="Cidade"
                        fullWidth
                        autoComplete="shipping address-level2"
                        variant="outlined"
                        value={senderCity}
                        onChange={e => setSenderCity(e.target.value)}
                    />
                </Grid>
                <Grid item xs={6} sm={2}>
                    <TextField
                        label="UF"
                        fullWidth
                        variant="outlined"
                        value={senderUf}
                        onChange={e => setSenderUf(e.target.value)}
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="O que deseja enviar?"
                        fullWidth
                        select
                        variant="outlined"
                        value={senderProduct}
                        onChange={e => setSenderProduct(e.target.value)}
                    >
                        <MenuItem key={1} value={10}>Caixa</MenuItem>
                        <MenuItem key={2} value={20}>Envelopes</MenuItem>
                        <MenuItem key={3} value={30}>Documentos</MenuItem>
                        <MenuItem key={4} value={40}>Outros</MenuItem>
                    </TextField>
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Modalidade"
                        fullWidth
                        select
                        variant="outlined"
                        value={modality}
                        onChange={e => setModality(e.target.value)}
                    >
                        <MenuItem key={1} value={10}>Same Day (Entregue no mesmo dia)</MenuItem>
                        <MenuItem key={2} value={20}>Next Day (13h do dia seguinte)</MenuItem>
                    </TextField>
                </Grid>
                <Grid item xs={12}>
                    <FormControlLabel
                        control={<Checkbox color="secondary" name="saveAddress" value="yes" />}
                        label="Salvar este endereço como favorito"
                        value={senderSaveAdress}
                        onChange={e => setSenderSaveAdress(e.target.value)}
                    />
                </Grid>
            </Grid>
        </React.Fragment>
    );
}