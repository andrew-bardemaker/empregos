import api from "../services/api";

export default function getUFCity(state) {

    function getUrl() {

        if (state) {

            return (`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${state}/municipios`);

        } else {

            return ('https://servicodados.ibge.gov.br/api/v1/localidades/estados/');

        };
    };

    return api.get(getUrl());
};