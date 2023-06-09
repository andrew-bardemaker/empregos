import { Card, Grid, Typography } from "@mui/material";
import { useState } from "react";
import { useEffect } from "react";
import { useContext } from "react";
import { useParams } from "react-router-dom"
import AuthContext from "../contexts/AuthProvider";
import parse from 'html-react-parser';
import api from "../services/api"

export default function NotificationDetails() {

    const { userData } = useContext(AuthContext);

    const { id } = useParams();

    const [data, setData] = useState({});

    useEffect(() => {
        api.get(`notificacoes-visualizar.php?id=${id}&user_id=${userData.id}`)
            .then(res => {
                if (res.data.success) {
                    setData(res.data.notificacao[0]);
                } else {
                    alert('Houve um erro ao receber as informações de notificação!');
                }
            })
            .catch(err => {
                alert('Houve um erro ao receber as informações de notificação!')
                console.log(err);
            })
    }, [id])


    return (
        <Grid container>
            <Grid component={Card} item xs={12} m={'1em'} p="1em">
                <Typography variant='h2'>
                    {data.titulo}
                </Typography>
                <Typography mt={'1em'}>
                    {parse(data.texto || '')}
                </Typography>
            </Grid>
        </Grid>

    )
}