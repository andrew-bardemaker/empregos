import { FacebookRounded, Instagram, LinkedIn, YouTube } from "@mui/icons-material";
import { Box, CardMedia, IconButton, Link, Typography } from "@mui/material";
import { Link as RouterLink } from 'react-router-dom';


export default function Footer() {

    const styles = {
        centralizer: {
            p: '2.5em 0',
            borderTop: '5px solid #000',
            borderImage: 'linear-gradient( 45deg , #ffebbb, #ffd969, #7b511e, #4b3113) 1',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            width: '100vw',
        },
        content: {
            m: '0 auto',
            width: '100%',
            height: '100%',
            maxWidth: '1280px',
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center'
        },
        information: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'row',
            p: '0 2.5em'
        },
        footerImg: {
            height: '5em',
            width: '5em'
        },
        iconButtons: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column'
        }
    };

    return (
        <Box sx={{
            ...styles.centralizer, display: {
                xs: 'none',
                sm: 'block'
            }
        }}>
            <Box sx={styles.content}>
                <Box sx={styles.information}>
                    <Box>
                        <Link href="https://dedstudio.com.br/bejobs/landing_page/" target="_blank" >
                            <Typography fontWeight={700}>
                                Para empresas
                            </Typography>
                        </Link>
                        <Link component={RouterLink} to="Cadastro">
                            <Typography fontWeight={700}>
                                Crie sua conta
                            </Typography>
                        </Link>
                        <Link component={RouterLink} to="TermosEPrivacidade">
                            <Typography fontWeight={700}>
                                Termos de Privacidade
                            </Typography>
                        </Link>
                        <Link component={RouterLink} to="Suporte">
                            <Typography fontWeight={700}>
                                Suporte
                            </Typography>
                        </Link>
                    </Box>
                </Box>
                <Box sx={styles.iconButtons}>
                    <CardMedia
                        component={'img'}
                        image="https://dedstudio.com.br/bejobs/images/vagas/default.jpg"
                        alt="Logo Bejobs"
                        sx={styles.footerImg}
                    />
                    <Box>
                        <IconButton href="https://www.instagram.com/somosbejobs/" target="_blank" >
                            <Instagram fontSize="large" color="primary" />
                        </IconButton>
                        <IconButton href="https://www.linkedin.com/in/bejobs-app-de-vagas-b128aa26a/" target="_blank" >
                            <LinkedIn fontSize="large" color="primary" />
                        </IconButton>
                    </Box>
                </Box>
            </Box>
        </Box>
    );
};