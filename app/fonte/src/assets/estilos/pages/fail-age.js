export const useStyles = theme => ({
    root: {
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        minHeight: '100vh',
        backgroundColor: '#FF8A00',
        color: '#444444 !important',
    },
    paper: {
        marginTop: theme.spacing(10),
      },
    main: {
        marginTop: theme.spacing(4),
        marginBottom: theme.spacing(2),
    },
    footer: {
        padding: theme.spacing(3, 2),
        marginTop: 'auto',
        backgroundColor: '#FF8A00',
        textAlign:'center',
    },
    avatar: {
        marginTop: theme.spacing(8),
        width: 180,
        height: 180,
      },
      submit: {
        margin: theme.spacing(3, 0, 2),
        backgroundColor: '#444444',
        color: '#fff'
      },
      title: {
        textTransform: 'uppercase',
        color: '#444444',
        fontSize: 40,
        fontFamily: 'Bebas Neue, cursive !important',
      },
      titleSmall: {
        // textTransform: 'uppercase',
        fontSize: 18,
        color: '#444444',
      },
      titleMedium: {
        textTransform: 'uppercase',
        fontSize: 16,
        color: '#444444',
      },
});