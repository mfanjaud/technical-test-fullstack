import { Component, Input } from '@angular/core';

/**
 * @ngModule ElementsModule
 *
 * @usageNotes
 * ```
 *    <ca-icon color="LocalColorStyle" svgClass="LocalSvgClass" name="LocalSvgIdentifier">
 *
 *    <ca-icon [ngColor]="{'LocalColorStyle': true, ...}" [ngName]="{'LocalSvgIdentifier': true, ...}">
 * ```
 *
 * @description
 *
 * Affiche l'image identifiée dans l'attribut `name` ou `[ngName]` avec la couleur définit dans l'attribut "color" ou "[ngColor]"
 *
 * - `color` et `ngColor` - Nom du style local de couleur définit dans la feuille de style du composant.
 * - `name` et `ngName` - Identifiant du `symbol` SVG définit dans le composant `svg-definition` (Voir page de preview)
 *
 * Les attributs `ngColor` et `ngName` acceptent:
 * - `String` - Une seule valeur entre quote: `[ngColor]="'black'"` ou une expression ternaire:  `[ngColor]="myVar === 1 ? 'black' : 'white'"`
 * - `Object` - Liste de clés/valeurs. Les clés sont les couleurs ou noms, appliquées si l'expression en valeur retourne True. Si plusieurs expressions sont, seule la dernière clés est appliquée (on ne peut afficher qu'une seule couleur et qu'une seule image).
 */
@Component({
  selector: 'app-icon',
  templateUrl: './icon.component.html',
  styleUrls: ['./icon.component.scss'],
})
export class IconComponent {
  @Input() get name(): string {
    return this._name;
  }

  set name(value: string) {
    console.log('name value: ', value);
    if (typeof value === 'string') {
      this._name = value;
    } else {
      Object.keys(value).forEach((name) => {
        if (!!value[name]) this._name = name;
      });
    }
  }

  @Input() get color(): string {
    return this._color;
  }

  set color(value: string | { [value: string]: any }) {
    // If null ou undefined => couleur par défaut
    if (value) {
      if (typeof value === 'string') {
        this._color = value;
      } else {
        Object.keys(value).forEach((color) => {
          if (!!value[color]) this._color = color;
        });
      }
    } else {
      this._color = 'black';
    }
  }

  @Input() svgClass?: string;

  private _name = '';
  private _color = '';

  constructor() {
    console.log(this.name, this.svgClass);
  }

  get absUrl() {
    return window.location.href;
  }
}
