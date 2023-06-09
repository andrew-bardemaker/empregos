import {
    React,
    useState,
} from 'react';

import { Box, Card, createTheme, Grid, LinearProgress, Typography } from '@mui/material';
import Profile from './Register/Profile';
import PersonalData from './Register/PersonalData';
import ContactAddress from './Register/ContactAddress';
import Gallery from './Register/Gallery';
import Success from './Register/Success';
import Payment from './Register/Payment';
import api from '../services/api';

export default function Register() {

    const [screen, setScreen] = useState(0);
    const [progress, setProgress] = useState(0);

    const [userType, setUserType] = useState('');
    const [userData, setUserData] = useState({});

    const [contactInfo, setContactInfo] = useState({});

    function LinearProgressWithLabel({ value }) {
        return (
            <Box sx={{ display: 'flex', alignItems: 'center' }}>
                <Box sx={{ width: '100%', mr: 1 }}>
                    <LinearProgress variant="determinate" value={value} />
                </Box>
                <Box sx={{ minWidth: 35 }}>
                    <Typography variant="body2" color="primary">{`${Math.round(value)}%`}</Typography>
                </Box>
            </Box>
        );
    };

    function handleChangeUser(userType) {
        setUserType(userType);
        setScreen(screen + 1);
    };

    function handleChangeUserData(data) {
        setUserData(data);
        setScreen(screen + 1);
    };

    function handleChangeContactInfo(data) {
        setContactInfo(data);
        setScreen(screen + 1);
    };

    function createUser(data) {
        api.post('cadastrar-novo-usuario.php', {
            userType,
            estrangeiro: userData.estrangeiro,
            userData: {
                ...userData,
                ...contactInfo,
                ...data
            }
        })
            .then(res => {
                if (res.data.success) {
                    setScreen(screen + 1);
                } else {
                    alert('Houve um erro ao criar seu usuário! Tente novamente mais tarde!')
                };
            })
    };

    function handleChangeGallery(data) {

        if (userType === '0') {
            createUser(data);
        } else {
            setScreen(screen + 1);
        };
    };

    function handleCreateCompany(data) {
        createUser(data);
        setScreen(screen + 1);
    };

    function handleChangeProgress(value) {
        setProgress(value);
    };

    function back() {
        if (screen >= 1) {
            setScreen(screen - 1);
        } else {
            setScreen(1);
        };
    };

    function Pages() {

        switch (screen) {
            case 0:
                return <Profile handleChangeUser={handleChangeUser} handleChangeProgress={handleChangeProgress} />
            case 1:
                return <PersonalData handleChangeProgress={handleChangeProgress} handleChangeUserData={handleChangeUserData} userType={userType} back={back} />
            case 2:
                return <ContactAddress handleChangeProgress={handleChangeProgress} handleChangeContactInfo={handleChangeContactInfo} userType={userType} back={back} />
            case 3:
                return <Gallery userType={userType} back={back} handleChangeProgress={handleChangeProgress} handleChangeGallery={handleChangeGallery} />
            case 4:
                return userType === '0' ? <Success userType={userType} back={back} handleChangeProgress={handleChangeProgress} /> : <Payment userType={userType} back={back} handleChangeProgress={handleChangeProgress} handleCreateCompany={handleCreateCompany} />
            default:
                return <Success userType={userType} back={back} handleChangeProgress={handleChangeProgress} />
        };
    };

    const styles = createTheme({
        alignCenter: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center'
        },
        centralize: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            p: '2em 1em 5em 1em ',
        },
        userProfile: {
            m: 'auto',
            height: 150,
            width: 150,
            borderRadius: '50%'
        },
        userProfileBox: {
            m: '2em 1em',
            p: '1em',
            width: '100%',
            maxWidth: '1280px'
        },
        checkboxContainer: {
            display: 'flex',
            flexDirection: "row",
            alignItems: "center",
        },
        label: {
            margin: 10,
        },
        editButton: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'row',
            width: '100%',
            marginTop: '1.5em'
        },
        textField: {
            mt: '1.5em',
            width: '100%'
        },
        uploadBox: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column'
        },
        uploadButton: {
            width: '8em',
            height: '8em'
        },
        uploadText: {
            fontWeight: '700',
            textAlign: 'center',
            m: '1em'
        }
    });

    return (
        <Grid container spacing={1} component={Card} sx={styles.userProfileBox}>
            <Grid item xs={12}>
                <Typography variant="h2">
                    Cadastro
                </Typography>
            </Grid>
            <Grid item xs={12}>
                <LinearProgressWithLabel variant="determinate" value={progress} />
            </Grid>
            <Pages />
        </Grid>
    )
};