export const useStyles = theme => ({
    appBarSpacer: theme.mixins.toolbar,
    topSpace: {
      height: theme.spacing(15),
    },
    root: {
      width: '100%',
      height: '100%',
      backgroundColor: '#fff',
  
    },
    profile: {
      backgroundColor: '#fff',
    },
    profileInvite: {
        paddingTop: theme.spacing(4),
        paddingBottom: theme.spacing(4),
      },
    container: {
      paddingTop: theme.spacing(2),
      paddingBottom: theme.spacing(0),
    },
    paper: {
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      backgroundColor: '#fff',
      borderRadius: 8,
      marginBottom: theme.spacing(3),
    },
    form: {
      width: '100%', // Fix IE 11 issue.
    },
    submit: {
      margin: theme.spacing(0, 0, 0),
    },
    title: {
      textTransform: 'uppercase',
      color: '#444444',
      fontSize: 40,
      fontFamily: 'Bebas Neue, cursive !important',
    },
    subtitle: {
        textTransform: 'uppercase',
        color: '#FF8A00',
        fontSize: 36,
        fontFamily: 'Bebas Neue, cursive !important',
      },
    titleSmall: {
      fontSize: 16,
      color: '#444444',
    },
    forgotPasswordLink: {
      marginTop: theme.spacing(2),
      fontSize: 16,
    }
  })