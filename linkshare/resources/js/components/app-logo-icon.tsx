import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGSVGElement>) {
    return (
        <svg
            {...props}
            viewBox="0 0 180 140"
            xmlns="http://www.w3.org/2000/svg"
        >
            <line
                x1="44"
                y1="26"
                x2="108"
                y2="26"
                stroke="#DE2C43"
                strokeWidth="2.5"
                strokeLinecap="round"
                fill="none"
            />

            <line
                x1="44"
                y1="26"
                x2="76"
                y2="82"
                stroke="#DE2C43"
                strokeWidth="8"
                strokeLinecap="round"
                fill="none"
            />

            <line
                x1="108"
                y1="26"
                x2="76"
                y2="82"
                stroke="#DE2C43"
                strokeWidth="8"
                strokeLinecap="round"
                fill="none"
            />

            <line
                x1="108"
                y1="26"
                x2="148"
                y2="62"
                stroke="#DE2C43"
                strokeOpacity="0.35"
                strokeWidth="1.5"
                strokeLinecap="round"
                strokeDasharray="4 3"
                fill="none"
            />

            <line
                x1="76"
                y1="82"
                x2="44"
                y2="118"
                stroke="#DE2C43"
                strokeOpacity="0.35"
                strokeWidth="8"
                strokeLinecap="round"
                strokeDasharray="4 3"
                fill="none"
            />

            <line
                x1="76"
                y1="82"
                x2="128"
                y2="108"
                stroke="#DE2C43"
                strokeWidth="8"
                strokeLinecap="round"
                fill="none"
            />

            <polygon
                points="44,8 56,15 56,29 44,36 32,29 32,15"
                fill="#DE2C43"
            />

            <polygon
                points="108,8 120,15 120,29 108,36 96,29 96,15"
                fill="#DE2C43"
            />

            <polygon
                points="76,64 88,71 88,85 76,92 64,85 64,71"
                fill="#DE2C43"
            />

            <polygon
                points="44,100 56,107 56,121 44,128 32,121 32,107"
                fill="#DE2C43"
            />

            <polygon
                points="128,90 140,97 140,111 128,118 116,111 116,97"
                fill="#DE2C43"
            />

            <polygon
                points="148,44 160,51 160,65 148,72 136,65 136,51"
                fill="#DE2C43"
                fillOpacity="0.4"
            />
        </svg>
    );
}
