import { useContext } from "react";
import AuthContext from "../contexts/AuthProvider";

export default function RouteWrapper({ loggedComponent, defaultComponent, isPrivate }) {

    const { userData } = useContext(AuthContext);

    if (userData && !isPrivate) {
        return defaultComponent //direciona para página privada.
    } else if (!userData && isPrivate) {
        return defaultComponent //direciona para página inicial.
    } else {
        return userData ? loggedComponent : defaultComponent
    };
};