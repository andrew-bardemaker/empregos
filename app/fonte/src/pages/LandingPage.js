import React from 'react';
import { CardMedia, createTheme, Grid, Typography, Button, Card, useTheme, Box, TextField, Paper, FormControlLabel, Checkbox } from '@mui/material';
import { WhatsApp } from '@material-ui/icons';
import { Link } from 'react-router-dom'
import bgMobile from '../images/bgMobile.jpg';
import bgDesktop from '../images/bgDesktop.jpg';

const styles = createTheme({
    container: {
        width: '100%',
        height: '100%',
        margin: 'auto',
    },
    welcomeBg: {
        backgroundImage: {
            xs: `url(${bgMobile})`,
            sm: `url(${bgDesktop})`
        },
        backgroundSize: 'cover',
        backgroundPosition: {
            xs: 'center',
        },
        backgroundPositionY: {
            sm: '-160px'
        },
        width: '100%',
        height: '40em',
    },
    bgFilter: {
        bgcolor: 'rgb(0,0,0,.5)',
        width: '100%',
        height: '100%',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
    },
    welcome: {
        width: '100%',
        maxWidth: '1280px',
    },
    services: {
        width: '100%',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center'
    },
    partner: {
        width: '100%',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        backgroundImage: 'url(https://bejobs.me/wp-content/uploads/2020/07/banner-parceiro-1-1.png)',
        backgroundSize: 'cover',
        backgroundPosition: 'center'
    },
    offer: {
        width: '100%',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
    },
    field: {
        [`& fieldset`]: {
            borderRadius: '8px',
        }
    }
});

class LandingPage extends React.Component {
    state = {
        name: '',
        email: '',
        phone: '',
        company: '',
        message: '',
        acceptTerms: false
    }

    render() {
        return (
            <Grid
                container
                direction="column"
                justifyContent="center"
                alignItems="center"
                sx={styles.container}>
                <Grid item xs={12} sx={styles.welcome}>
                    <Box sx={styles.welcomeBg} >
                        <Box sx={styles.bgFilter}>
                            <Box sx={{
                                width: {
                                    xs: '90%',
                                    sm: '60%'
                                },
                                maxWidth: '1280px'
                            }}>
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
                                        width: {
                                            xs: '100%',
                                            sm: '50%'
                                        },
                                        fontSize: {
                                            xs: '1.875em',
                                            sm: '2.125em'
                                        }
                                    }}>
                                    O seu jeito de enviar e receber encomendas
                                </Typography>
                                <Link to={'/'}>
                                    <Button variant="contained" sx={{
                                        width: {
                                            xs: '100%',
                                            sm: '30%'
                                        },
                                        height: '3.5em',
                                        mt: '2rem',
                                        color: 'white'
                                    }}>
                                        Leve minha encomenda
                                    </Button>
                                </Link>
                            </Box>
                        </Box>
                    </Box>
                </Grid>
                <Grid item xs={12} sx={styles.services}>
                    <Grid container spacing={3} justifyContent={"center"} sx={{ maxWidth: '1280px' }}>
                        <Grid item xs={12} sx={{
                            display: "flex",
                            justifyContent: "center",
                            alignItems: "center"
                        }}>
                            <Typography sx={{
                                textAlign: "center",
                                width: '80%',
                                marginTop: '2em',
                                marginBottom: '1em',
                                color: '#444444',
                                fontSize: {
                                    xs: '1.5em',
                                    lg: "2em"
                                }
                            }} variant={"h2"} fontWeight={'900'}  >
                                Ajudamos você a receber e enviar encomendas de forma prática e inteligente.
                            </Typography>
                        </Grid>
                        <Grid item xs={12}>
                            <Box sx={{ height: '4px', width: '80%', bgcolor: '#ff8a00', margin: 'auto' }} />
                        </Grid>
                        <Grid item xs={11} sm={5}>
                            <Card
                                elevation={3}
                                sx={{
                                    borderRadius: '8px',
                                }}>
                                <CardMedia
                                    component="img"
                                    height="100%"
                                    image="https://bejobs.me/wp-content/uploads/2020/07/empresario-1.png"
                                    alt="Logo Llevo"
                                />
                                <Typography sx={{ textAlign: "center", fontWeight: 900, fontSize: '1.25em', color: "#FF8A00", marginTop: '3.75em', marginBottom: '1em' }}>
                                    RECEBA NO MESMO DIA
                                </Typography>
                                <Typography sx={{ textAlign: "center", fontSize: '1em', width: '90%', margin: 'auto', height: '6em' }}>
                                    Com a modalidade SAME DAY, você recebe suas encomendas no mesmo dia (Capitais e regiões metropolitanas)
                                </Typography>
                            </Card>
                        </Grid>
                        <Grid item xs={11} sm={5}>
                            <Card
                                elevation={3}
                                sx={{
                                    borderRadius: '8px'
                                }}>
                                <CardMedia
                                    component="img"
                                    height="100%"
                                    image="https://bejobs.me/wp-content/uploads/2020/07/UP2L77yY0P-1.jpg"
                                    alt="Logo Llevo"
                                />
                                <Typography sx={{ textAlign: "center", fontWeight: 900, fontSize: '1.25em', color: "#FF8A00", marginTop: '3.75em', marginBottom: '1em' }}>
                                    RECEBA NO DIA SEGUINTE
                                </Typography>
                                <Typography sx={{ textAlign: "center", fontSize: '1em', width: '90%', margin: 'auto', height: '6em' }}>
                                    Sua encomenda entregue em até 13hs do dia seguinte. Para isso conte com a modalidade NEXT DAY.
                                </Typography>
                            </Card>
                        </Grid>
                        <Grid item xs={12} sx={{ display: 'flex', justifyContent: 'center', alignItems: "center" }}>
                            <CardMedia
                                component="img"
                                image="https://bejobs.me/wp-content/uploads/2020/07/carr.png"
                                alt="Logo Llevo"
                                sx={{ width: "7em", border: 'none' }}
                            />
                        </Grid>
                    </Grid>
                </Grid>
                <Grid item xs={12} sx={styles.partner}>
                    <Box sx={{
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        width: '100%',
                        height: '100%',
                        bgcolor: 'rgb(0,0,0,.5)',
                    }}>
                        <Box sx={{ width: '100%', maxWidth: '1280px', height: '22em', display: 'flex', justifyContent: 'center', alignItems: 'center', flexDirection: 'column' }}>
                            <Box sx={{ width: '80%', maxWidth: '1280px' }}>
                                <Typography variant='h2' fontSize={'2em'} fontWeight={900} color={'white'}>
                                    Ganhe enquanto dirige
                                </Typography>
                                <Typography fontSize={'0.875em'} color={'white'} sx={{ mt: '0.5rem' }}>
                                    Leve gentileza e ganhe por isso, simples assim =)
                                </Typography>
                                <Link to={'/parceiro'}>
                                    <Button variant="contained" sx={{ mt: '1.5rem', color: 'white' }}>
                                        Cadastre-se
                                    </Button>
                                </Link>

                            </Box>
                        </Box>
                    </Box>
                </Grid>
                <Grid item xs={12} sx={styles.offer}>
                    <Grid container sx={{ maxWidth: '1280px', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                        <Grid item xs={11} sm={5}
                            sx={{
                                display: 'flex',
                                justifyContent: {
                                    xs: 'center',
                                    sm: 'left'
                                },
                                flexDirection: 'column'
                            }}>
                            <Typography
                                sx={{
                                    fontWeight: 'bold',
                                    fontSize: '3em',
                                    marginTop: '2rem',
                                    textAlign: {
                                        xs: 'center',
                                        sm: 'left'
                                    },
                                }}>
                                #EuLLevo
                            </Typography>
                            <Typography sx={{
                                textAlign: {
                                    xs: 'center',
                                    sm: 'left'
                                }
                            }}>
                                Sua entrega criando uma rede de economia  compartilhada.
                            </Typography>
                            <Typography sx={{
                                mt: '2em',
                                textAlign: {
                                    xs: 'center',
                                    sm: 'left'
                                }
                            }}>
                                Envie e receba suas encomendas, escolha uma modalidade que melhor se adéqua a você ou a sua empresa!
                            </Typography>
                            <Button
                                startIcon={<WhatsApp />}
                                variant={"contained"}
                                sx={{
                                    mt: '2em',
                                    color: 'white',
                                    width: {
                                        xs: '100%',
                                        sm: '40%'
                                    },
                                    backgroundColor: '#167a3c',
                                    '&:hover': {
                                        backgroundColor: '#0e4f27'
                                    }
                                }}>
                                Tire suas dúvidas
                            </Button>
                        </Grid>
                        <Grid item xs={11} sm={5}
                            sx={{
                                display: "flex",
                                justifyContent: "center",
                                alignItems: "center"
                            }}>
                            <CardMedia
                                component="img"
                                image="https://bejobs.me/wp-content/uploads/2020/07/llogo-base-gif-novo.gif"
                                alt="Logo Llevo"
                                sx={{ borderBottomWidth: 0 }}
                            />
                        </Grid>
                    </Grid>
                </Grid>
                <Grid item xs={12}
                    sx={{
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        background: '#ff8a00',
                        width: '100%'
                    }}>
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
                                    Fale conosco
                                </Typography>
                                <Typography sx={{ mt: '1rem' }}>
                                    Estamos aguardando seu contato!
                                </Typography>
                            </Grid>
                            <Grid item sm={3}>
                                <TextField
                                    required
                                    label="Nome"
                                    fullWidth
                                    autoComplete="given-name"
                                    variant="outlined"
                                    value={this.name}
                                    onChange={e => this.setState({ name: e.target.value })}
                                    sx={styles.field}
                                />
                            </Grid>
                            <Grid item sm={3}>
                                <TextField
                                    required
                                    label="E-mail"
                                    fullWidth
                                    autoComplete="given-name"
                                    variant="outlined"
                                    value={this.name}
                                    onChange={e => this.setState({ name: e.target.value })}
                                    sx={styles.field}
                                />
                            </Grid>
                            <Grid item sm={3}>
                                <TextField
                                    required
                                    label="Telefone"
                                    fullWidth
                                    autoComplete="given-name"
                                    variant="outlined"
                                    value={this.name}
                                    onChange={e => this.setState({ name: e.target.value })}
                                    sx={styles.field}
                                />
                            </Grid>
                            <Grid item sm={3}>
                                <TextField
                                    required
                                    label="Empresa"
                                    fullWidth
                                    autoComplete="given-name"
                                    variant="outlined"
                                    value={this.name}
                                    onChange={e => this.setState({ name: e.target.value })}
                                    sx={styles.field}
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <TextField
                                    required
                                    label="Mensagem"
                                    fullWidth
                                    autoComplete="given-name"
                                    variant="outlined"
                                    multiline
                                    rows={4}
                                    value={this.name}
                                    onChange={e => this.setState({ name: e.target.value })}
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
                                <Button variant='contained' sx={{ color: 'white' }}>
                                    Enviar mensagem
                                </Button>
                            </Grid>
                        </Grid>
                    </Paper>
                </Grid>
            </Grid>
        )
    }
}

export default LandingPage;