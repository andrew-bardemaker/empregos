import { CreditCard, CreditScore, DateRange, Email, Person, Pix } from "@mui/icons-material";
import { Accordion, AccordionDetails, AccordionSummary, Button, Grid, InputAdornment, MenuItem, Select, TextField, Typography } from "@mui/material";
import { useEffect, useState } from "react";
import api from "../../services/api";

export default function Payment({ back, handleChangeProgress, handleCreateCompany }) {

    const [data, setData] = useState({});
    const [error, setError] = useState(false);

    const [cardName, setCardName] = useState('');
    const [cardNumber, setCardNumber] = useState('');
    const [cardValidation, setCardValidation] = useState('');
    const [cardCVV, setCardCVV] = useState('');

    const [pixEmail, setPixEmail] = useState('');

    const [paymentMethod, setPaymentMethod] = useState('');

    const [plan, setPlan] = useState('');

    function handlePay() {

        if (pixEmail !== '') {
            handleCreateCompany();
        } else if (paymentMethod !== '') {
            if (paymentMethod === 0) {

            };
        };
    };

    function getValues() {

        api.get('pega-valores-plano.php')
            .then(res => {
                if (res.data.success) {
                    setData(res.data.valores);
                } else {
                    setError(true);
                };
            })
            .catch(error => {
                console.log(error);
                setError(true);
            });
    };

    function resetPIX() {
        setPixEmail('');
        setPlan('');
    };

    function resetCC() {
        setCardName('');
        setCardNumber('');
        setCardValidation('');
        setCardCVV('');
        setPaymentMethod('');
        setPlan('');
    };

    useEffect(() => {
        handleChangeProgress(95);
        getValues();
    }, []);

    useEffect(() => {
        pixEmail !== '' && resetCC();
        cardName !== '' && resetPIX();

    }, [pixEmail, cardName]);

    return (
        <Grid container spacing={1}>
            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Pagamento</Typography>
                <Accordion>
                    <AccordionSummary>
                        <CreditCard color={"primary"} />
                        <Typography fontWeight={700} color={"primary"} ml=".25em">
                            Cartão de Crédito
                        </Typography>
                    </AccordionSummary>
                    <AccordionDetails>
                        <Grid container spacing={1}>
                            <Grid item xs={12} sm={6}>
                                <TextField InputProps={{
                                    startAdornment: (
                                        <InputAdornment position="end">
                                            <Person />
                                        </InputAdornment>
                                    ),
                                }} value={cardName} onChange={e => setCardName(e.target.value)} fullWidth placeholder="Nome do Titular" />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled={cardName === ''}
                                    InputProps={{
                                        startAdornment: (
                                            <InputAdornment position="end">
                                                <CreditCard />
                                            </InputAdornment>
                                        ),
                                    }} value={cardNumber} onChange={e => setCardNumber(e.target.value)} fullWidth placeholder="Número do Cartão" />
                            </Grid>
                            <Grid item xs={6} sm={3}>
                                <TextField
                                    disabled={cardNumber === ''}
                                    InputProps={{
                                        startAdornment: (
                                            <InputAdornment position="end">
                                                <DateRange />
                                            </InputAdornment>
                                        ),
                                    }} value={cardValidation} onChange={e => setCardValidation(e.target.value)} fullWidth placeholder="Validade" />
                            </Grid>
                            <Grid item xs={6} sm={3}>
                                <TextField
                                    disabled={cardValidation === ''}
                                    InputProps={{
                                        startAdornment: (
                                            <InputAdornment position="end">
                                                <CreditScore />
                                            </InputAdornment>
                                        ),
                                    }} value={cardCVV} onChange={e => setCardCVV(e.target.value)} fullWidth placeholder="CVV" />
                            </Grid>
                            <Grid item xs={6} sm={3}>
                                <Select
                                    disabled={cardCVV === ''}
                                    fullWidth
                                    value={paymentMethod}
                                    displayEmpty
                                    onChange={e => setPaymentMethod(e.target.value)}
                                >
                                    <MenuItem value={''}>- Selecione uma modalidade -</MenuItem>
                                    <MenuItem value={0}>Débito</MenuItem>
                                    <MenuItem value={1}>Crédito</MenuItem>
                                </Select>
                            </Grid>
                            <Grid item xs={6} sm={3}>
                                <Select
                                    disabled={paymentMethod === ''}
                                    fullWidth
                                    value={plan}
                                    displayEmpty
                                    onChange={e => setPlan(e.target.value)}
                                >
                                    <MenuItem value={''}>- Selecione um plano -</MenuItem>
                                    <MenuItem value={30}>Diamante Mensal {paymentMethod === 1 && '(1X)'} - R$497,00</MenuItem>
                                    <MenuItem value={30}>Diamante Trimestral {paymentMethod === 1 && '(3X)'} - R$891,00</MenuItem>
                                    <MenuItem value={30}>Quartzo Mensal {paymentMethod === 1 && '(1X)'} - R$297,00</MenuItem>
                                    <MenuItem value={30}>Quartzo Trimestral {paymentMethod === 1 && '(3X)'} - R$441,00</MenuItem>
                                </Select>
                            </Grid>
                            <Grid item xs={12}>
                                <Button disabled={!(paymentMethod !== '' && plan !== '')} fullWidth size="large" variant="contained" startIcon={<CreditCard />} color="success">
                                    {
                                        (paymentMethod !== '' && plan !== '') ?
                                            "Pagar agora!"
                                            :
                                            "Preencha os dados acima!"
                                    }
                                </Button>
                            </Grid>
                        </Grid>
                    </AccordionDetails>
                </Accordion>
                <Accordion>
                    <AccordionSummary>
                        <Pix color={"primary"} />
                        <Typography fontWeight={700} color={"primary"} ml=".25em">
                            PIX
                        </Typography>
                    </AccordionSummary>
                    <AccordionDetails>
                        <Grid container spacing={1}>
                            <Grid item xs={12} sm={6}>
                                <TextField InputProps={{
                                    startAdornment: (
                                        <InputAdornment position="end">
                                            <Email />
                                        </InputAdornment>
                                    ),
                                }} value={pixEmail} onChange={e => setPixEmail(e.target.value)} fullWidth placeholder="Email*" />
                            </Grid>
                            <Grid item xs={6} sm={3}>
                                <Select
                                    disabled={pixEmail.length < 5}
                                    fullWidth
                                    value={plan}
                                    displayEmpty
                                    onChange={e => setPlan(e.target.value)}
                                >
                                    <MenuItem value={''}>- Selecione um plano -</MenuItem>
                                    <MenuItem value={30}>Diamante Mensal - R$497,00</MenuItem>
                                    <MenuItem value={30}>Diamante Trimestral - R$891,00</MenuItem>
                                    <MenuItem value={30}>Quartzo Mensal - R$297,00</MenuItem>
                                    <MenuItem value={30}>Quartzo Trimestral - R$441,00</MenuItem>
                                </Select>
                            </Grid>
                            {console.log(data)}
                            <Grid item xs={12}>
                                <Button disabled={!(pixEmail !== '' && plan !== '')} fullWidth size="large" variant="contained" startIcon={<Pix />} color="success">
                                    {
                                        pixEmail !== '' && plan !== '' ?
                                            "Pagar agora!"
                                            :
                                            "Preencha os campos acima!"
                                    }
                                </Button>
                            </Grid>

                        </Grid>
                    </AccordionDetails>
                </Accordion>
            </Grid>
            <Grid item xs={12} sx={{
                display: 'flex',
                justifyContent: 'space-between'
            }}>
                <Button onClick={() => { back() }}>
                    Voltar
                </Button>
            </Grid>
        </Grid>
    )
}