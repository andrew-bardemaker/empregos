
import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import { noAuto } from '@fortawesome/fontawesome-svg-core';

const drawerWidth = 240;

const useStyles = makeStyles((theme) => ({
    root: {
        // display: 'block',
    },
    title: {
        textTransform: 'uppercase',
        color: '#444444',
        fontSize: 40,
        fontFamily: 'Bebas Neue, cursive !important',
    },
    titleSmall: {
        marginTop:10,
        textTransform: 'uppercase',
        color: '#444444',
        fontSize: 24,
        fontFamily: 'Bebas Neue, cursive !important',
      },
    iconAreaIcon: {
        color: '#FF8A00',
        // fontSize: 26,
    },
}));

export default (useStyles);