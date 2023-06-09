import { Card, Typography } from "@mui/material";
import { useState } from "react";
import api from "../services/api";
import parse from 'html-react-parser';
import { Box } from "@mui/system";

export default function PrivacyPolicy() {

    const [data, setData] = useState('');

    api.get('termos-de-uso.php')
        .then(res => {
            setData(res.data.termos);
        })

    return (
        <Box sx={{
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center'
        }}>
            <Card sx={{
                maxWidth: '1280px',
                m: '2em 1em',
            }}>
                <Typography m={'1em'}>
                    {parse(data)}
                </Typography>
            </Card>
        </Box>
    )
};