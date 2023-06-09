import React from 'react';
import { Link } from 'react-router-dom'
import { Typography, Button, Container, Grid, IconButton } from '@material-ui/core';
import FacebookIcon from '@material-ui/icons/Facebook';
import InstagramIcon from '@material-ui/icons/Instagram';
import LinkedInIcon from '@material-ui/icons/LinkedIn';
import AppleIcon from '@material-ui/icons/Apple';
import AdbIcon from '@material-ui/icons/Adb';

export default function Footer() {

    function scrollToTop() {
        document.getElementById("top").scroll(0, 0)
    }
    return (
        <footer className='footer'>
            <Button onClick={scrollToTop} className='buttonFooter' size='large' color="secondary" variant='contained'>
                Voltar ao Topo
            </Button>
            <Container maxWidth="lg">
                <Grid container
                    direction="column" spacing={4}>
                    <Grid item container
                        justifyContent="space-between"
                        alignItems="flex-start"
                        direction="row" xs={12} lg={12} spacing={2}>
                        <Grid item xs={12} lg={2}>
                        </Grid>

                        <Grid item xs={12} lg={2}>
                            <Typography component="h1" variant="h5" className="titleFooter">
                                Acesso Rápido
                            </Typography>

                            <Link to="/promocoes">Promoções</Link><br />
                            <Link to="/login">Entrar</Link>


                        </Grid>
                        <Grid item xs={12} lg={2}>
                            <Typography component="h1" variant="h5" className="titleFooter">
                                Ficou com dúvida?
                            </Typography>

                            <Link to="/faq">FAQ</Link><br />
                            <Link to="/ajuda">Ajuda</Link><br />
                            <Link to="/termosdeuso">Termos de Uso</Link>

                        </Grid>

                        <Grid item xs={12} lg={2}>
                            <Typography component="h1" variant="h5" className="titleFooter">
                                Baixe o app
                            </Typography>
                            <IconButton href="" aria-label="Apple" className="iconFooter">
                                <AppleIcon />
                            </IconButton>
                            <IconButton href="" aria-label="Instagram" className="iconFooter">
                                <AdbIcon />
                            </IconButton>
                        </Grid>

                        <Grid item xs={12} lg={2}>
                            <Typography component="h1" variant="h5" className="titleFooter">
                                Siga-nos!
                            </Typography>
                            <IconButton href="https://it-it.facebook.com/bejobs.me/" target={'_blank'} targearia-label="Facebook" className="iconFooter">
                                <FacebookIcon />
                            </IconButton>
                            <IconButton href="https://www.instagram.com/bejobs.me" target={'_blank'} aria-label="Instagram" className="iconFooter">
                                <InstagramIcon />
                            </IconButton>
                            <IconButton href="https://gh.linkedin.com/company/bejobs-tecnologia" target={'_blank'} aria-label="LinkedIn" className="iconFooter">
                                <LinkedInIcon />
                            </IconButton>
                        </Grid>
                    </Grid>

                    <Grid item container
                        direction="column"
                        justifyContent="center"
                        alignItems="center"
                        align="center">
                        <Grid item xs={12} lg={12}>
                            <Typography variant="body2">
                                &copy; 2022 Llevo - Inscrito sob CNPJ nº XXXXXXXX
                            </Typography>
                            <Typography variant="caption">
                                Desenvolvido por <a href="https://dedstudio.com.br/" target="_blank" rel='noopener noreferrer'>DeD Studio</a>.
                            </Typography>
                        </Grid>
                    </Grid>

                </Grid>
            </Container>
        </footer>
    );
}