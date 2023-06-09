import { Notifications, NotificationsOff } from '@mui/icons-material';
import { Badge, Button, Drawer, Grid, IconButton, Typography } from '@mui/material';
import { Box } from '@mui/system';
import React, { useContext, useEffect, useState } from 'react'
import { Link } from 'react-router-dom';
import api from '../services/api';
import AuthContext from './AuthProvider';

const NotificationContext = React.createContext({})

function NotificationProvider({ children }) {

    const [openNotification, setOpenNotification] = useState(false);

    const [userNotifications, setUserNotifications] = useState([]);
    const [totalNotifications, setTotalNotifications] = useState(0);

    const { userData } = useContext(AuthContext);

    const styles = {
        noNotifications: {
            width: '100%',
            height: '100%',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column',
            p: '1em 0'
        }
    };

    function refreshNotifications() {
        userData && api.post('notificacoes.php', {
            user_id: userData.id
        })
            .then(res => {
                if (res.data.success) {
                    setTotalNotifications(res.data.total);
                    setUserNotifications(res.data.notificacoes);
                } else {
                    alert('Houve um erro ao carregar suas notificações!');
                };
            })
            .catch(err => {
                alert('Houve um erro ao carregar suas notificações!');
                console.log(err);
            });
    };

    useEffect(() => {
        refreshNotifications();
        console.log()
    }, [userData]);

    function CustomNotifications() {

        return (
            <Drawer
                anchor={"top"}
                open={openNotification}
                onClose={() => { setOpenNotification(false) }}
                sx={{ zIndex: 2500 }}
            >
                {
                    userNotifications ?
                        <Grid container maxHeight={'150px'} pb="1em">
                            <Grid item xs={12}>
                                <Typography variant="h2" textAlign='center' p='.25em 0'>
                                    Suas notificações
                                </Typography>
                            </Grid>
                            {
                                userNotifications.map((obj, index) => {
                                    return (
                                        <Grid item xs={12} key={index} p=".25em">
                                            <Button sx={{ display: 'flex', justifyContent: 'space-between', p: '1em' }} fullWidth component={Link} variant={obj.status === '1' ? "contained" : 'outlined'} to={`/NotificacaoDetalhes/${obj.id}`} onClick={() => { setOpenNotification(false); refreshNotifications() }}>
                                                <Typography fontWeight={700}>
                                                    {obj.titulo}
                                                </Typography>
                                            </Button>
                                        </Grid>
                                    )
                                })
                            }
                        </Grid>
                        :
                        <Box sx={styles.noNotifications}>
                            <NotificationsOff color="error" fontSize="large" />
                            <Typography variant="h2" textAlign={'center'} color="primary">
                                Você ainda não possui notificações
                            </Typography>
                        </Box>
                }
            </Drawer>
        )
    };

    function NotificationBadge() {

        return userData && (
            <IconButton onClick={() => setOpenNotification(true)}>
                <Badge badgeContent={totalNotifications} color="error">
                    <Notifications sx={{ color: 'white' }} />
                </Badge>
            </IconButton>
        )

    };

    const value = {
        userNotifications,
        totalNotifications,
        refreshNotifications,
        NotificationBadge,
        CustomNotifications
    };

    return (
        <NotificationContext.Provider
            value={value}
        >
            {children}
        </NotificationContext.Provider>
    );
};

export const useNotifications = () => useContext(NotificationContext);

export { NotificationProvider };