import * as React from 'react';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import TextField from '@mui/material/TextField';
import FormControlLabel from '@mui/material/FormControlLabel';
import Checkbox from '@mui/material/Checkbox';

export default function RecipientForm() {

    const [name, setName] = React.useState('');
    const [email, setEmail] = React.useState('');
    const [whatsapp, setWhatsapp] = React.useState('');
    const [cep, setCep] = React.useState('');
    const [adress, setAdress] = React.useState('');
    const [number, setNumber] = React.useState('');
    const [complement, setComplement] = React.useState('');
    const [district, setDistrict] = React.useState('');
    const [city, setCity] = React.useState('');
    const [uf, setUf] = React.useState('');
    const [product, setProduct] = React.useState('');
    const [modality, setModality] = React.useState('');
    const [saveAdress, setSaveAdress] = React.useState(false);

    return (
        <React.Fragment>
            <Typography variant="h6" gutterBottom>
                Informações - Destinatário
            </Typography>
            <Typography variant="p" color="#ff8a00" gutterBottom>
                Insira o endereço de DESTINO da encomenda
            </Typography>
            <Grid container spacing={3} paddingTop={2}>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Nome completo do destinatário"
                        fullWidth
                        autoComplete="given-name"
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Email do destinatário"
                        fullWidth
                        autoComplete="family-name"
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12}>
                    <TextField
                        required
                        label="Whatsapp (DDD) do destinatário"
                        fullWidth
                        autoComplete="shipping address-line1"
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="CEP do destinatário"
                        fullWidth
                        autoComplete="shipping postal-code"
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        label="Endereço do destinatário"
                        fullWidth
                        autoComplete="shipping address-line2"
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        required
                        label="Cidade do destinatário"
                        fullWidth
                        autoComplete="shipping address-level2"
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12} sm={6}>
                    <TextField
                        label="UF do destinatário"
                        fullWidth
                        variant="outlined"
                    />
                </Grid>
                <Grid item xs={12}>
                    <FormControlLabel
                        control={<Checkbox color="secondary" name="saveAddress" value="yes" />}
                        label="Salvar este endereço como favorito"
                    />
                </Grid>
            </Grid>
        </React.Fragment>
    );
}