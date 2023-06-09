import { CheckCircle } from "@mui/icons-material";
import { Box, Button, Grid, Typography } from "@mui/material";
import { useEffect } from "react";
import { Link } from "react-router-dom";


export default function Success({ handleChangeProgress }) {

    useEffect(() => {
        handleChangeProgress(100);
    }, []);

    return (
        <Grid container spacing={1} p="15vh 0">
            <Grid item xs={12}>
                <Box sx={{ width: '100%', display: 'flex', justifyContent: 'center' }}>
                    <CheckCircle sx={{ fontSize: '80px' }} color="success" />
                </Box>
                <Typography sx={{ mt: '1em', textAlign: 'center' }} variant='h2'>Usuário Criado com Sucesso!</Typography>
            </Grid>
            <Grid item xs={12} sx={{ display: 'flex', justifyContent: 'center' }}>
                <Button variant="contained" component={Link} to="/Login" m="1em auto 0 auto">Acesse agora sua conta!</Button>
            </Grid>
        </Grid>
    );
};