import Router from "./routes";
import { ThemeProvider } from "@mui/system";
import global from "./theme/global";
import { AuthProvider } from "./contexts/AuthProvider";
import { NotificationProvider } from "./contexts/NotificationProvider";

function App() {
  return (
    <ThemeProvider theme={global}>
      <NotificationProvider>
        <AuthProvider>
          <Router />
        </AuthProvider>
      </NotificationProvider>
    </ThemeProvider>
  )
}

export default App;