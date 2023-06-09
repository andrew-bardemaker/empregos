import React from 'react';
import { Link } from 'react-router-dom'
import clsx from 'clsx';
import { makeStyles, useTheme } from '@material-ui/core/styles';
import { Drawer, Badge, AppBar, Toolbar, IconButton, Divider, Typography, Box } from '@material-ui/core';
import CssBaseline from '@material-ui/core/CssBaseline';
import List from '@material-ui/core/List';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingBag, faUser, faBars, faHome } from '@fortawesome/free-solid-svg-icons'
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import useStyles from '../assets/estilos/pages/menu';
import CartInfo from './Cart';
import { mainListItems } from './MenuItens';
import { logoBrancaTransparente } from '../assets/images/logo';

export default function AppMenu() {
  const classes = useStyles();
  const cartInfo = <CartInfo />;
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

          <Box className={classes.title}>
            <Link to='/home'><img className={classes.logo} src={logoBrancaTransparente} /></Link>
          </Box>

          <Link to='/home'><IconButton
            color="inherit"
            aria-label="open drawer"
            edge="end"
            className={clsx(open && classes.hide)}
          >
            <FontAwesomeIcon icon={faHome} />
          </IconButton></Link>

          <Link to='/login'><IconButton
            color="inherit"
            aria-label="open drawer"
            edge="end"
            className={clsx(open && classes.hide)}
          >
            <FontAwesomeIcon icon={faUser} />
          </IconButton></Link>

          <Link to='/sacola'><IconButton
            color="inherit"
            aria-label="open drawer"
            edge="end"
            onClick={handleDrawerOpen}
            className={clsx(open && classes.hide)}
          >
            <Badge overlap="rectangular" badgeContent={cartInfo} color="secondary"><FontAwesomeIcon icon={faShoppingBag} /></Badge>
          </IconButton></Link>

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
