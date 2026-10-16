## Tutorial 3: Blender to Unity FBX Export

**Title:** Export Blender Assets to Unity: FBX Settings and Common Errors  
**Slug:** `blender-to-unity-fbx`  
**Category:** Game Engine Export  
**Difficulty:** Beginner  
**Time:** 35 minutes  
**Software:** Blender 4.x + Unity (any LTS release)  
**Blender Version:** 4.2 LTS  
**Prerequisites:** A finished Blender prop with UV coordinates applied. The barrel from the low-poly modeling tutorial is a good candidate. Basic familiarity with the Unity Editor - you should know how to create a project and drag files into the Project window.  
**Next Tutorial URL:** (leave empty - end of workflow)  

**Excerpt:**  
Export a Blender prop into Unity via FBX. This tutorial covers the correct export settings, scale and axis conventions, material setup, and the most common errors you will encounter when a Blender asset lands in a Unity scene for the first time.

---

## What you will do

By the end of this tutorial, your low-poly barrel will be visible in a Unity scene with its geometry and UV coordinates intact. You will understand:
- Why Blender and Unity use different scale and axis conventions
- Which FBX export settings produce a reliable result
- How to set up materials in Unity so the barrel does not appear blank
- What to check when the import looks wrong

## The scale and axis problem

Before anything else, understand why a Blender prop often arrives in Unity looking wrong.

**Scale:** Blender's default unit is the metre, and Unity uses the same convention. However, Blender applies a scale transform to objects that is not always visible. If you model an object and never applied the scale in Blender (Object Mode → Ctrl + A → All Transforms), the FBX exporter may export the object at 1:1 Blender units, and Unity may interpret it differently.

**Axes:** Blender uses a Z-up coordinate system. Unity uses Y-up. The FBX format handles this conversion when the export settings are configured correctly, but if they are not, your barrel will arrive in Unity rotated 90 degrees, lying on its side.

These two issues cause the majority of "broken" imports. Fix them at the source, before exporting.

## Step 1 - Apply transforms in Blender

Open your barrel file in Blender. In Object Mode, select the barrel. Press Ctrl + A and choose **All Transforms**. This applies any pending scale, rotation, and location transforms to the mesh data, making the object's transform values read as 1, 1, 1 for scale, 0, 0, 0 for rotation, and 0, 0, 0 for location.

Check the Item panel (N key) to confirm: Scale should read 1.000 on all three axes.

If your barrel was modeled at an unusual size, check its dimensions in the Item panel. A real-world barrel is approximately 0.6–0.9 metres in diameter and 0.8–1.0 metres tall. If your barrel reads as 80 × 120 centimetres in Blender, it should appear at approximately the same size in Unity.

## Step 2 - Set up materials in Blender before export

Unity will import the barrel's mesh data regardless of whether you have materials assigned. However, materials tell Unity where to look for texture data and how to display the surface.

In Blender:
1. Select the barrel in Object Mode
2. Open the Material Properties tab (the sphere icon)
3. If no material is assigned, click **+ New**
4. Name the material clearly - for example, `barrel_mat` - because this name will appear in Unity
5. You do not need complex shader nodes for an FBX export. A basic Principled BSDF material with a Base Color is enough.

If you have painted or applied a texture to the material, Unity will import it as part of the FBX if the texture is embedded, or it will look for the texture in the same folder as the FBX if it is not embedded. This tutorial covers the simpler case: importing with a blank material that you assign textures to in Unity.

## Step 3 - Export the FBX

Go to **File → Export → FBX (.fbx)**.

The FBX export panel will open on the right. Set the following options:

**Include:**
- Selected Objects: on (if you only want to export the barrel)
- Object Types: Mesh

**Transform:**
- Scale: 1.00
- Apply Scalings: FBX All
- Forward: -Z Forward
- Up: Y Up
- Apply Unit: on
- Use Space Transform: on

**Geometry:**
- Smoothing: Face
- Export Subdivision Surface: off

**Armature:**
- Leave defaults (no armature for a static prop)

The most important settings here are **Forward: -Z Forward** and **Up: Y Up**. These convert Blender's Z-up coordinate system to Unity's Y-up system during export. Without these settings, the barrel will be rotated incorrectly when it appears in Unity.

Click **Export FBX**. Save the file to a location you can find - for example, a folder called `GameAssetLab_Export`.

## Step 4 - Import into Unity

Open Unity and create a new 3D project, or open an existing one.

In the Unity Editor's Project window, navigate to the Assets folder (or a subfolder of your choice). Drag the exported `.fbx` file from Windows Explorer into the Project window.

Unity will process the file and create a model asset. If your barrel has no texture, it will appear with a blank grey material in the Scene view.

Click the imported model in the Project window to see the Import Settings in the Inspector.

Check the following on the **Model** tab:
- **Scale Factor:** should be 1
- **Convert Units:** should be checked (this handles the Blender unit to Unity unit conversion)
- **Import BlendShapes:** off for a static prop
- **Mesh Compression:** None or Low for now

Check the **Rig** tab:
- **Animation Type:** None (for a static prop with no skeleton)

Click **Apply** if you changed anything.

## Step 5 - Place the barrel in a scene

Drag the model from the Project window into the Scene view. The barrel should appear upright, at approximately the correct scale.

If the barrel is:
- **Lying on its side** - your Forward/Up export settings were wrong. Go back to Blender, re-export with -Z Forward and Y Up, and reimport.
- **Much too large or too small** - the Apply Unit option was off during export, or transforms were not applied. Fix in Blender and re-export.
- **Appearing pink** - Unity cannot find the material's shader. Create a new material in Unity (Assets → Create → Material), assign it to the barrel in the Inspector, and set the shader to Standard or Universal Render Pipeline (depending on your Unity project).

## Step 6 - Assign materials and textures in Unity

If you have a texture image for the barrel:
1. Drag the texture file (`.png`, `.jpg`, or `.tga`) into the Assets folder in Unity
2. Select the Material in the Project window (Unity creates it automatically during FBX import, in a Materials subfolder next to the FBX)
3. In the Inspector, find the **Albedo** slot (or **Base Map** in URP) and drag your texture into it
4. The barrel in the Scene view should now display the texture

If you do not have a texture yet, assign a flat colour using the Albedo field's colour picker. The barrel should not look blank - even a solid colour confirms that the material connection is working.

## Step 7 - Verify the result

Before considering the import complete, check:

- **The barrel is upright** (Y axis pointing up in Unity's world space)
- **The scale is approximately correct** - a 0.8 m barrel should appear roughly door-handle height when next to a default Unity character
- **UVs are intact** - if you apply a checker texture material in Unity, the grid should appear consistently across the surface without distortion
- **No pink materials** - all material slots are filled
- **No console errors** related to missing textures or import failures

## Common errors and their causes

**"Texture not found" warning in Unity's Console**  
Unity looked for the texture in the same folder as the FBX but did not find it. Either embed textures in the FBX (Path Mode → Embed in Blender's export panel), or copy the texture file to the same folder as the FBX before importing.

**Barrel arrives with incorrect rotation (usually lying flat)**  
The export axis settings were not correct. Re-export from Blender with Forward: -Z Forward, Up: Y Up.

**The barrel is 100 times too small or too large**  
Transforms were not applied before export, or Convert Units was unchecked in Unity's Import Settings. Apply transforms in Blender (Ctrl + A → All Transforms), re-export, and check Apply Unit in the export panel.

**The mesh appears with inverted normals (inside faces visible)**  
Some faces had their normals flipped before export. In Blender, enter Edit Mode, select all, and press Shift + N → Recalculate Outside. Re-export.

**The material appears solid pink in Unity**  
Unity cannot find or apply the material's shader. Right-click the material in the Project window and select **Reimport**. If using the Universal Render Pipeline, ensure you are using URP materials, not Built-in ones.

## A note on keyword demand for this topic

The search data collected for this tutorial indicates that export-related terms such as "blender to unity fbx export" returned no suggestions in the keyword tool used during research. This suggests either very low search volume or that the tool did not index this specific phrase. This tutorial was written because the workflow is genuinely useful to learners - not because of verified keyword demand. Ranking outcomes for this page are uncertain and should be evaluated after the site has been indexed and Search Console data is available.

## What comes next

With the barrel in a Unity scene, the full asset workflow is complete: model → UV unwrap → export → in-engine placement. From here, you might explore:
- Applying a hand-painted or substance-painted texture to give the barrel visual character
- Duplicating the barrel and modifying geometry to create variations (oil drums, larger casks)
- Combining multiple props into a scene to practice scene composition in Unity

Browse the [Tutorials index](/tutorials/) to find more asset production guides.

## External references

- [Blender Manual: FBX Export](https://docs.blender.org/manual/en/5.0/addons/import_export/scene_fbx.html)
- [Unity Manual: Importing a Model](https://docs.unity3d.com/Manual/ImportingModelFiles.html)
- [Unity Manual: Model Import Settings - Model Tab](https://docs.unity3d.com/Manual/FBXImporter-Model.html)
