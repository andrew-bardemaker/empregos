import { BrowserRouter, Route, Routes } from 'react-router-dom';
import Layout from './components/Layout/Layout';
import RouteWrapper from './components/RouteWrapper';
import Applications from './screens/Applications';
import CandidateDetails from './screens/CandidateDetails';
import CompanyJobDetails from './screens/CompanyJobDetails';
import JobCreate from './screens/JobCreate';
import EditProfile from './screens/EditProfile';
import Home from './screens/Home';
import JobApplications from './screens/JobApplications';
import JobDetails from './screens/JobDetails';
import JobEdit from './screens/JobEdit';
import Jobs from './screens/Jobs';
import LandingPage from './screens/LandingPage';
import Login from './screens/Login';
import PrivacyPolicy from './screens/PrivacyPolicy';
import Register from './screens/Register';
import { useContext } from 'react';
import AuthContext from './contexts/AuthProvider';
import NotificationDetails from './screens/NotificationDetails';

function Router() {

    const { userData } = useContext(AuthContext);

    function dinamicHome() {
        if (userData?.tipo_usuario === '1') {
            return <Jobs />;
        } else {
            return <Home />;
        }
    }

    return (
        <BrowserRouter basename='/bejobs/projeto/'>
            <Layout>
                <Routes>
                    <Route exact path='/' element={dinamicHome()} />

                    <Route path='/Home' element={dinamicHome()} />

                    <Route path='/TermosEPrivacidade' element={<PrivacyPolicy />} />

                    <Route path='/VagaDetalhes/:id' element={<JobDetails />} />

                    <Route path='/VagaCandidaturas/:id' element={<RouteWrapper isPrivate loggedComponent={<JobApplications />} defaultComponent={<Login />} />} />

                    <Route path='/CadastrarVagas' element={<RouteWrapper isPrivate loggedComponent={<JobCreate />} defaultComponent={<Login />} />} />

                    <Route path='/MinhasCandidaturas' element={<RouteWrapper isPrivate loggedComponent={<Applications />} defaultComponent={<Login />} />} />

                    <Route path='/MinhasVagas' element={<RouteWrapper isPrivate loggedComponent={<Jobs />} defaultComponent={<Login />} />} />

                    <Route path='/MinhasVagasDetalhes/:id' element={<RouteWrapper isPrivate loggedComponent={<CompanyJobDetails />} defaultComponent={<Login />} />} />

                    <Route path='/VisualizarCandidato/:id' element={<RouteWrapper isPrivate loggedComponent={<CandidateDetails />} defaultComponent={<Login />} />} />

                    <Route path='/EditarVaga/:id' element={<RouteWrapper isPrivate loggedComponent={<JobEdit />} defaultComponent={<Login />} />} />

                    <Route path='/NotificacaoDetalhes/:id' element={<RouteWrapper isPrivate loggedComponent={<NotificationDetails />} defaultComponent={<Login />} />} />

                    <Route path='/Login' element={<RouteWrapper isPrivate loggedComponent={<Home />} defaultComponent={<Login />} />} />

                    <Route path='/Cadastro' element={<RouteWrapper loggedComponent={<Home />} defaultComponent={<Register />} />} />

                    <Route path='/EditarPerfil' element={<RouteWrapper isPrivate loggedComponent={<EditProfile />} defaultComponent={<Home />} />} />

                    <Route path='/LandingPage' element={<RouteWrapper isPrivate loggedComponent={<Home />} defaultComponent={<LandingPage />} />} />
                </Routes>
            </Layout>
        </BrowserRouter>
    );
}

export default Router;