import React from 'react';
import { CardMedia, createTheme, Grid, Typography, Button, Box, TextField, Paper, FormControlLabel, Checkbox } from '@mui/material';
import { DirectionsCar } from '@material-ui/icons';
import bgPartner from '../assets/images/partner.jpg';
import AdapterDateFns from '@mui/lab/AdapterDateFns';
import { ptBR } from 'date-fns/locale';
import { DatePicker, LocalizationProvider } from '@mui/lab';

const styles = createTheme({
    container: {
        width: '100%',
        height: '100%',
        margin: 'auto',
    },
});

class Partner extends React.Component {
    state = {
        name: '',
        lastname: '',
        email: '',
        phone: '',
        birthday: '',
        city: '',
        acceptTerms: false
    }

    render() {
        return (
            <Box sx={{
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                height: '100vh',
                width: '100vw',
                backgroundImage: `url(${bgPartner})`,
                backgroundSize: 'cover',
                backgroundPositionY: '-160px'
            }}>
                <Box sx={{
                    display: 'flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    height: '100vh',
                    width: '100vw',
                    bgcolor: 'rgb(0,0,0,.5)',
                }}>
                    <Grid container
                        sx={{
                            width: '100%',
                            maxWidth: '1280px',
                            m: 'auto'
                        }} >
                        <Grid item xs={12} sm={6}>
                            <Box>
                                <CardMedia
                                    component="img"
                                    image="https://bejobs.me/wp-content/uploads/2020/04/vertical_tagline_on_transparent_by_logaster-1200x1200.png"
                                    alt="Logo Llevo"
                                    sx={{
                                        width: "12em",
                                        border: 'none',
                                        margin: {
                                            xs: 'auto',
                                            sm: 0
                                        }
                                    }}
                                />
                                <Typography variant='h1' fontWeight={900} color={"white"}
                                    sx={{
                                        mt: '2rem',
                                        fontSize: {
                                            xs: '1.875em',
                                            sm: '3em'
                                        }
                                    }}>
                                    Torne-se
                                    <br />
                                    um GO-LLEVER
                                </Typography>
                                <Typography sx={{ mt: '1rem', color: '#ff8a00' }}>
                                    Ganhe dinheiro dirigindo!
                                </Typography>
                            </Box>
                        </Grid>
                        <Grid item xs={12} sm={6}>
                            <Paper
                                elevation={3}
                                sx={{
                                    padding: '2em',
                                    margin: '1.5em',
                                    borderRadius: '8px',
                                }}>
                                <Grid container sx={{ width: '100%', maxWidth: '1280px' }}
                                    spacing={3}>
                                    <Grid item xs={12}>
                                        <Typography variant='h2' fontSize={'2em'} fontWeight={900} color={'primary'}>
                                            Venha ser um parceiro de entregas da Llevo.
                                        </Typography>
                                        <Typography sx={{ mt: '1rem' }}>
                                            Inscreva-se aqui!
                                        </Typography>
                                    </Grid>
                                    <Grid item sm={6}>
                                        <TextField
                                            required
                                            label="Nome"
                                            fullWidth
                                            autoComplete="given-name"
                                            variant="outlined"
                                            value={this.state.name}
                                            onChange={e => this.setState({ name: e.target.value })}
                                            sx={styles.field}
                                        />
                                    </Grid>
                                    <Grid item sm={6}>
                                        <TextField
                                            required
                                            label="Sobrenome"
                                            fullWidth
                                            variant="outlined"
                                            value={this.state.lastname}
                                            onChange={e => this.setState({ lastname: e.target.value })}
                                            sx={styles.field}
                                        />
                                    </Grid>
                                    <Grid item sm={6}>
                                        <TextField
                                            required
                                            label="E-mail"
                                            fullWidth
                                            autoComplete="given-name"
                                            variant="outlined"
                                            value={this.state.email}
                                            onChange={e => this.setState({ email: e.target.value })}
                                            sx={styles.field}
                                        />
                                    </Grid>
                                    <Grid item sm={6}>
                                        <TextField
                                            required
                                            label="Telefone"
                                            fullWidth
                                            variant="outlined"
                                            value={this.state.phone}
                                            onChange={e => this.setState({ phone: e.target.value })}
                                            sx={styles.field}
                                        />
                                    </Grid>
                                    <Grid item sm={6}>
                                        <TextField
                                            required
                                            label="Cidade"
                                            fullWidth
                                            variant="outlined"
                                            value={this.state.city}
                                            onChange={e => this.setState({ city: e.target.value })}
                                            sx={styles.field}
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <FormControlLabel
                                            control={<Checkbox color="secondary" name="saveAddress" value="yes" />}
                                            label="Concordo com os Termos de Uso"
                                            value={this.state.acceptTerms}
                                            onChange={e => this.setState({ acceptTerms: e.target.value })}
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <Button startIcon={<DirectionsCar />} variant='contained' sx={{ color: 'white' }}>
                                            Enviar inscrição
                                        </Button>
                                    </Grid>
                                </Grid>
                            </Paper>
                        </Grid>
                    </Grid>
                </Box>
            </Box>
        )
    }
}

export default Partner;