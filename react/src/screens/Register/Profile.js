import { LocalHospital, MedicalServices } from "@mui/icons-material";
import { Button, Grid, Typography } from "@mui/material";
import { useEffect } from "react";

export default function Profile({ handleChangeUser, handleChangeProgress }) {

    const styles = {
        icon: {
            fontSize: '80px'
        },
        button: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column',
            boxShadow: '3px 3px 15px 2px',
            height: '100%',
            p: '2em',

        }
    };

    function setType(type) {
        handleChangeUser(type);
    };

    useEffect(() => {
        handleChangeProgress(0);
    }, [])

    return (
        <Grid container>
            <Grid item xs={12} sm={6} p={'1em'}>
                <Button fullWidth sx={styles.button} onClick={() => setType('0')}>
                    <MedicalServices sx={styles.icon} />
                    <Typography variant={'h2'} mt=".5em">
                        Profissional
                    </Typography>
                    <Typography mt=".5em" width={'70%'}>
                        Está em busca de oportunidades na sua área de atuação. Perfil de médicos, dentistas ou esteticistas.
                    </Typography>
                </Button>
            </Grid>

            <Grid item xs={12} sm={6} p={'1em'}>
                <Button fullWidth sx={styles.button} onClick={() => setType('1')}>
                    <LocalHospital sx={styles.icon} />
                    <Typography variant={'h2'} mt=".5em">
                        Instituição
                    </Typography>
                    <Typography mt=".5em" width={'70%'}>
                        Está em busca de profissionais para constituirem sua equipe. Perfil de clínicas, hospitais, salões e estéticas.
                    </Typography>
                </Button>
            </Grid>
        </Grid>
    )
}