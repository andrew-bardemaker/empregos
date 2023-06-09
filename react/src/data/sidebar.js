import { Add, Home, Login, Message, Person, PersonAdd, PrivacyTip, Work } from "@mui/icons-material";

export const userSidebar = [
    {
        icon: <Home color="primary" />,
        title: 'Home',
        path: '/',
        permission: 2
    },
    {
        icon: <Person color="primary" />,
        title: 'Meu perfil',
        path: '/EditarPerfil',
        permission: 2
    },
    {
        icon: <Work color="primary" />,
        title: 'Minhas candidaturas',
        path: '/MinhasCandidaturas',
        permission: 0
    },
    {
        icon: <Work color="primary" />,
        title: 'Minhas vagas',
        path: '/MinhasVagas',
        permission: 1
    },
    {
        icon: <Add color="primary" />,
        title: 'Cadastrar vagas',
        path: '/CadastrarVagas',
        permission: 1
    },
    {
        icon: <PrivacyTip color="primary" />,
        title: 'Termos de privacidade',
        path: '/TermosEPrivacidade',
        permission: 2
    }
];

export const unloggedSidebar = [
    {
        icon: <Login color="primary" />,
        title: 'Acessar conta',
        path: '/Login',
    },
    {
        icon: <PersonAdd color="primary" />,
        title: 'Criar conta',
        path: '/Cadastro',
    }
]