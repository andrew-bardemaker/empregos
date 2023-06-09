import React from 'react';
import { Link } from 'react-router-dom'
import clsx from 'clsx';
import { Drawer, AppBar, Toolbar, IconButton, Divider, Badge } from '@material-ui/core';
import CssBaseline from '@material-ui/core/CssBaseline';
import List from '@material-ui/core/List';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBars, faHome, faBell } from '@fortawesome/free-solid-svg-icons'
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import useStyles from '../assets/estilos/pages/menu';
import { mainListItems } from './MenuItensDriver';
import OpenOrders from './Orders';
import { logoBranca } from '../assets/images/logo';

export default function AppMenuDriver() {
  const classes = useStyles();
  const [open, setOpen] = React.useState(false);

  const handleDrawerOpen = () => {
    setOpen(true);
  };

  const handleDrawerClose = () => {
    setOpen(false);
  };

  return (
    <div className={classes.root}>
      <CssBaseline />
      <AppBar
        position="fixed"
        className={clsx(classes.appBar, {
          [classes.appBarShift]: open,
        })}
      >
        <Toolbar className={classes.toolbar}>

          <div className={classes.title}>
          <Link to='/homeentregador'><img className={classes.logo} src={logoBranca} /></Link>
          </div>

          <Link to='/homeentregador'><IconButton
            color="inherit"
            aria-label="open drawer"
            edge="end"
            className={clsx(open && classes.hide)}
          >
            <FontAwesomeIcon icon={faHome} />
          </IconButton></Link>

          <IconButton
            color="inherit"
            aria-label="open drawer"
            edge="end"
            onClick={() => {localStorage.setItem('filtro', '1'); window.location.reload()}}
            className={clsx(open && classes.hide)}
          >
            <Badge overlap="rectangular" badgeContent={<OpenOrders/>} color="secondary"><FontAwesomeIcon icon={faBell} /></Badge>
          </IconButton>

          <IconButton
            color="inherit"
            aria-label="open drawer"
            edge="end"
            onClick={handleDrawerOpen}
            className={clsx(open && classes.hide)}
          >
            <FontAwesomeIcon icon={faBars} />
          </IconButton>

        </Toolbar>
      </AppBar>

      <Drawer
        className={classes.drawer}
        variant="persistent"
        anchor="right"
        open={open}
        classes={{
          paper: classes.drawerPaper,
        }}
      >
        <div className={classes.drawerHeader}>
          <IconButton onClick={handleDrawerClose}>
            <ChevronRightIcon />
            {/* {theme.direction === 'rtl' ? <ChevronLeftIcon /> : <ChevronRightIcon />} */}
          </IconButton>
           <div className={classes.minHeight}> Olá! Bem-vindo!</div>
        </div>
        <Divider />
        <List className={classes.listMenu}>{mainListItems}</List>
      </Drawer>
    </div>
  );
}
