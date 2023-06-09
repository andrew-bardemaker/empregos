
import React from 'react';
import { makeStyles } from '@material-ui/core/styles';

const drawerWidth = 240;

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
  },
  appBar: {
    color: '#FFFF',
    backgroundColor: '#ff8a00',
    transition: theme.transitions.create(['margin', 'width'], {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
  },
  appBarShift: {
    width: `calc(100% - ${drawerWidth}px)`,
    transition: theme.transitions.create(['margin', 'width'], {
      easing: theme.transitions.easing.easeOut,
      duration: theme.transitions.duration.enteringScreen,
    }),
    marginRight: drawerWidth,
  },
  toolbar: {
    minHeight: theme.spacing(11),
    width: '100%',
    maxWidth: '1280px',
    margin: 'auto'
  },
  logo:{
    width: theme.spacing(9),
    marginTop: theme.spacing(1),
  },
  title: {
    alignItems: 'center',
    flexGrow: 1,
    textTransform: 'uppercase',
    color: '#444444',
    marginLeft:'15px',
    fontFamily: 'Bebas Neue, cursive !important',
  },
  hide: {
    display: 'none',
  },
  drawer: {
    width: drawerWidth,
    flexShrink: 0,
  },
  drawerPaper: {
    width: drawerWidth,
    backgroundColor:'#F9F9F9',
    borderLeft: 'none !important',
  },
  drawerHeader: {
    display: 'flex',
    alignItems: 'center',
    padding: theme.spacing(0, 1),
    // necessary for content to be below app bar
    ...theme.mixins.toolbar,
    justifyContent: 'flex-start',
    textTransform: 'uppercase',
    color: '#444444',
    fontSize:18,
    fontFamily: 'Bebas Neue, cursive !important',
    backgroundColor:'#F9F9F9 !important',
  },
  content: {
    flexGrow: 1,
    padding: theme.spacing(3),
    transition: theme.transitions.create('margin', {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
    marginRight: -drawerWidth,
  },
  contentShift: {
    transition: theme.transitions.create('margin', {
      easing: theme.transitions.easing.easeOut,
      duration: theme.transitions.duration.enteringScreen,
    }),
    marginLeft: 0,
  },
  listMenu:{
    color: '#444444 !important',
    fontSize: 16,
  },
  minHeight:{
    height:theme.spacing(11),
    display: 'flex',
    alignItems: 'center',
  }
}));

export default (useStyles);