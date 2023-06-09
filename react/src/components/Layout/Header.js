import { Menu as MenuIcon, Logout, ArrowForwardIos, Notifications, NotificationsOff } from "@mui/icons-material";
import { Badge, Box, Button, CardMedia, Divider, Drawer, Grid, IconButton, List, ListItem, ListItemButton, ListItemIcon, ListItemText, Typography } from "@mui/material";
import { useEffect } from "react";
import { Fragment, useContext, useState } from "react";
import { Link } from "react-router-dom";
import AuthContext from "../../contexts/AuthProvider";
import { unloggedSidebar, userSidebar } from "../../data/sidebar";
import api from "../../services/api";

export default function Header() {

    const { userData, signOut } = useContext(AuthContext);

    const [openDrawer, setOpenDrawer] = useState(false);
    const [openNotification, setOpenNotification] = useState(false);
    const [userNotifications, setUserNotifications] = useState(null);

    const [totalNotifications, setTotalNotifications] = useState(0);

    const styles = {
        centralizer: {
            position: 'fixed',
            backgroundImage: 'linear-gradient(to left, #1a3143, #142736 )',
            width: '100vw',
            height: '5em',
            zIndex: 2000,
            borderBottom: '5px solid #000',
            borderImage: 'linear-gradient( 45deg , #ffebbb, #ffd969, #7b511e, #4b3113) 1'
        },
        navBar: {
            maxWidth: '1280px',
            width: '100%',
            height: '5em',
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
            margin: 'auto',
        },
        navContent: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'row',
            p: '0 1em'
        },
        logo: {
            height: '4em',
            width: '4em'
        },
        headerMenu: {
            color: 'white'
        },
        drawer: {
            width: {
                xs: 250,
                sm: 350
            }
        },
        drawerHead: {
            height: '80px',
            display: 'flex',
            justifyContent: 'start',
            alignItems: 'center',
            padding: '0 0 0 16px'
        },
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

    function CustomDrawer() {

        return (
            <Drawer
                anchor="right"
                open={openDrawer}
                onClose={() => { setOpenDrawer(false) }}
                sx={{ zIndex: 2050 }}
            >
                <Box sx={styles.drawerHead}>
                    <IconButton onClick={() => setOpenDrawer(false)}>
                        <ArrowForwardIos />
                    </IconButton>
                </Box>
                <Divider />
                <List sx={styles.drawer}>
                    {
                        userData ?
                            <Fragment>
                                {userSidebar.map((obj, index) => {
                                    return (
                                        ((userData.tipo_usuario == obj.permission) || obj.permission === 2) && <ListItem key={index} disablePadding>
                                            <ListItemButton component={Link} to={obj.path} onClick={() => setOpenDrawer(false)}>
                                                <ListItemIcon>
                                                    {obj.icon}
                                                </ListItemIcon>
                                                <ListItemText primary={obj.title} />
                                            </ListItemButton>
                                        </ListItem>
                                    )
                                })}
                                <ListItem disablePadding>
                                    <ListItemButton component={Link} to="/Login" onClick={() => { signOut(); setOpenDrawer(false) }}>
                                        <ListItemIcon>
                                            <Logout color="primary" />
                                        </ListItemIcon>
                                        <ListItemText primary={'Sair'} />
                                    </ListItemButton>
                                </ListItem>
                            </Fragment>
                            :
                            <Fragment>
                                {
                                    unloggedSidebar.map((obj, index) => {
                                        return (
                                            <ListItem key={index} disablePadding>
                                                <ListItemButton component={Link} to={obj.path} onClick={() => setOpenDrawer(false)}>
                                                    <ListItemIcon>
                                                        {obj.icon}
                                                    </ListItemIcon>
                                                    <ListItemText primary={obj.title} />
                                                </ListItemButton>
                                            </ListItem>
                                        )
                                    })
                                }
                            </Fragment>
                    }
                </List>
            </Drawer>
        )
    };

    function CustomNotifications() {

        return (
            <Drawer
                anchor={"top"}
                open={openNotification}
                onClose={() => { setOpenNotification(false) }}
                sx={{ zIndex: 2100 }}
            >
                {
                    userNotifications ?
                        <Grid container maxHeight={'150px'} pb="1em" >
                            <Grid item xs={12}>
                                <Typography variant="h2" textAlign='center' p='.25em 0'>
                                    Suas notificações
                                </Typography>
                            </Grid>
                            {
                                userNotifications.map((obj, index) => {
                                    return (
                                        <Grid item xs={12} key={index} p=".25em">
                                            <Button sx={{ display: 'flex', justifyContent: 'space-between', p: '1em' }} fullWidth component={Link} variant={obj.status === '1' ? "contained" : 'outlined'} to={`/NotificacaoDetalhes/${obj.id}`} onClick={() => setOpenNotification(false)}>
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

    useEffect(() => {
        userData && api.post('notificacoes.php', {
            user_id: userData.id
        })
            .then(res => {
                if (res.data.success) {
                    setTotalNotifications(res.data.total);
                    setUserNotifications(res.data.notificacoes);
                } else {
                    alert('Houve um erro ao carregar suas notificações!');
                }
            })
            .catch(err => {
                alert('Houve um erro ao carregar suas notificações!');
                console.log(err);
            })
    }, [userData])


    return (
        <Box sx={styles.centralizer}>
            <Box sx={styles.navBar}>
                <Box sx={styles.navContent}>
                    <Link to={'/'}>
                        <CardMedia
                            sx={styles.logo}
                            component={'img'}
                            image="https://dedstudio.com.br/bejobs/admin/images/logo.png"
                            alt="Logo Bejobs"
                        />
                    </Link>
                    {
                        !userData &&
                        <Button ml={'.5em'} variant="text">
                            <Typography variant="h3" color={'white'}>
                                Quer anunciar vagas?
                            </Typography>
                        </Button>
                    }
                </Box>
                <Box sx={styles.navContent}>
                    {
                        userData && <IconButton onClick={() => setOpenNotification(true)}>
                            <Badge badgeContent={totalNotifications} color="error">
                                <Notifications sx={{ color: 'white' }} />
                            </Badge>
                        </IconButton>
                    }
                    <IconButton onClick={() => setOpenDrawer(true)}>
                        <MenuIcon sx={styles.headerMenu} />
                    </IconButton>
                    <CustomDrawer />
                    <CustomNotifications />
                </Box>
            </Box>
        </Box>
    )
}